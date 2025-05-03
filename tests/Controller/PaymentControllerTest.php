<?php 

namespace App\Tests\Controller;

use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentControllerTest extends WebTestCase
{
    /**
     * Test pour vérifier le processus de vérification avec des articles dans le panier.
     * Ce test simule un utilisateur avec des articles dans son panier et vérifie si la redirection vers
     * Stripe est correcte lorsque la session de paiement est créée.
     */
    public function testCheckoutWithItemsInCart()
    {
        $client = static::createClient();
        
        // Simule la session avec des articles dans le panier
        $cart = [
            [
                'id' => 1,
                'name' => 'Sweatshirt Test 1',
                'price' => 50,
                'image' => 'test1.jpg',
                'size' => 'M',
            ]
        ];
        $session = self::getContainer()->get('session.factory')->createSession();
        $session->set('cart', $cart);
        $session->save();
        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId()));

        // Crée un mock du StripeService pour simuler la session de paiement
        $stripeServiceMock = $this->createMock(StripeService::class);
        $stripeServiceMock->expects($this->once())
            ->method('createCheckoutSession')
            ->willReturn((object)['url' => 'http://example.com/checkout-session']);

        // Remplace le service StripeService dans le container
        self::getContainer()->set(StripeService::class, $stripeServiceMock);

        // Accède à la route de création de session de vérification
        $crawler = $client->request('GET', '/create-checkout-session');

        // Vérifie que l'utilisateur est redirigé vers la page de paiement de Stripe
        $this->assertResponseRedirects('http://example.com/checkout-session');
    }

    /**
     * Test pour vérifier le message d'avertissement lorsque le panier est vide.
     * Ce test vérifie qu'un utilisateur est redirigé vers la page du panier
     * avec un message d'avertissement lorsqu'il tente de procéder au paiement avec un panier vide.
     */
    public function testCheckoutWithEmptyCart()
    {
        $client = static::createClient();

        // Simule une session vide
        $session = self::getContainer()->get('session.factory')->createSession();
        $session->set('cart', []);
        $session->save();
        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId()));

        // Accède à la route de création de session de vérification
        $crawler = $client->request('GET', '/create-checkout-session');

        // Vérifie que l'utilisateur est redirigé vers le panier
        $this->assertResponseRedirects('/cart');

        // Récupère la session flash après la redirection
        $flashMessages = $session->getFlashBag()->get('warning');

        // Vérifie que le message flash "Votre panier est vide." est présent
        $this->assertContains('Votre panier est vide.', $flashMessages);
    }


    /**
     * Test pour vérifier le processus de paiement réussi.
     * Ce test simule un paiement réussi et vérifie que le message de succès est affiché,
     * ainsi que la suppression du panier de la session.
     */
    public function testPaymentSuccess()
    {
        $client = static::createClient();

        // Simule la session avec des articles dans le panier
        $cart = [
            [
                'id' => 1,
                'name' => 'Sweatshirt Test 1',
                'price' => 50,
                'image' => 'test1.jpg',
                'size' => 'M',
            ]
        ];
        $session = self::getContainer()->get('session.factory')->createSession();
        $session->set('cart', $cart);
        $session->save();
        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId()));

        // Accède à la page de succès
        $crawler = $client->request('GET', '/success');

        // Vérifie que la réponse est réussie
        $this->assertResponseIsSuccessful();

        // Vérifie que le texte exact est présent sur la page
        $this->assertSelectorTextContains('body', 'Votre paiement a été effectué avec succès !');

        // Vérifie que le panier est bien supprimé de la session
        $this->assertNull($session->get('cart'));
    }

    /**
     * Test pour vérifier la route d'annulation.
     * Ce test vérifie qu'un utilisateur est redirigé vers la page d'annulation
     * et qu'un message correspondant est affiché lorsqu'un paiement est annulé.
     */
    public function testPaymentCancel()
    {
        $client = static::createClient();

        // Accède à la page d'annulation
        $crawler = $client->request('GET', '/cancel');

        // Vérifie que la réponse est réussie
        $this->assertResponseIsSuccessful();

        // Vérifie que le texte exact est présent sur la page
        $this->assertSelectorTextContains('body', 'Paiement annulé');
    }

}
