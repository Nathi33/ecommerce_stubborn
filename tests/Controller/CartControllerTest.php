<?php

// tests/Controller/CartControllerTest.php

namespace App\Tests\Controller;

use App\Entity\Sweatshirt;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    // Test pour ajouter un sweatshirt au panier
    public function testAddToCart()
    {
        // Créez un client HTTP pour tester les requêtes
        $client = static::createClient();

        // Créez un sweatshirt fictif pour le test
        $sweatshirt = new Sweatshirt();
        $sweatshirt->setName('Test Sweatshirt');
        $sweatshirt->setPrice(50);
        $sweatshirt->setImage('test_image.jpg');
        $sweatshirt->setStockXs(10);
        $sweatshirt->setStockS(10);
        $sweatshirt->setStockM(10);
        $sweatshirt->setStockL(10);
        $sweatshirt->setStockXl(10);
        // Remarque: vous devez persister l'entité pour que son ID soit disponible
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($sweatshirt);
        $entityManager->flush();

        // Simulez une requête POST pour ajouter au panier avec la taille choisie
        $crawler = $client->request('POST', '/cart/add/'.$sweatshirt->getId(), [
            'size' => 'L',
        ]);

        // Vérifiez que la réponse est une redirection vers le panier
        $this->assertResponseRedirects('/cart');
    }

    // Test pour afficher le panier
    public function testCartDisplaysItemsCorrectly()
    {
        $client = static::createClient();
        $session = self::getContainer()->get('session.factory')->createSession();

        // Simule un article dans la session
        $cart = [
            [
                'id' => 1,
                'name' => 'Fake Sweat',
                'price' => 25,
                'image' => 'fake.jpg',
                'size' => 'M',
            ]
        ];
        $session->set('cart', $cart);
        $session->save();

        // Injecte la session dans le cookie du client
        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId()));

        // Accède à la page du panier
        $crawler = $client->request('GET', '/cart');

        // Vérifie que l'article est bien affiché
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Fake Sweat');
        $this->assertSelectorTextContains('body', '25');
        $this->assertSelectorTextContains('body', 'M');
    }

    // Test pour supprimer un sweatshirt du panier
    public function testRemoveFromCart(): void
    {
        $client = static::createClient();

        // Préparer la session avec un article
        $session = self::getContainer()->get('session.factory')->createSession();
        $cart = [[
            'id' => 1,
            'name' => 'Sweat test',
            'price' => 20,
            'image' => 'test.jpg',
            'size' => 'M',
        ]];
        $session->set('cart', $cart);
        $session->save();

        // Injecter la session dans le client (cookie)
        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId()));

        // Charger la page du panier (elle contient normalement les formulaires avec le token CSRF)
        $crawler = $client->request('GET', '/cart');

        // Récupérer le token CSRF depuis l’attribut _token du formulaire de suppression
        $token = $crawler->filter('form')->first()->filter('input[name="_token"]')->attr('value');

        // Envoyer la requête POST pour supprimer l'article avec le token récupéré
        $client->request('POST', '/cart/remove/0', [
            '_token' => $token,
        ]);

        // Vérifier que l'utilisateur est redirigé vers le panier
        $this->assertResponseRedirects('/cart');
    }

    // Test pour vérifier le total du panier
    public function testCartTotalCalculation(): void
    {
        $client = static::createClient();

        // Crée des articles fictifs dans le panier
        $cart = [
            [
                'id' => 1,
                'name' => 'Sweat test 1',
                'price' => 20,
                'image' => 'test1.jpg',
                'size' => 'M',
            ],
            [
                'id' => 2,
                'name' => 'Sweat test 2',
                'price' => 30,
                'image' => 'test2.jpg',
                'size' => 'L',
            ]
        ];

        // Simule la session
        $session = self::getContainer()->get('session.factory')->createSession();
        $session->set('cart', $cart);
        $session->save();
        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId()));

        // Accède à la page du panier
        $client->request('GET', '/cart');

        // Vérifie que le total affiché est correct (20 + 30 = 50)
        $this->assertSelectorTextContains('body', '50');
    }
}
