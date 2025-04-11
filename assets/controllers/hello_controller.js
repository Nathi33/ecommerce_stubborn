import { Controller } from "@hotwired/stimulus";

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */

export default class extends Controller {
  connect() {
    // Ne rien modifier ici, pas de remplacement de texte
    console.log("Le contrôleur Stimulus est maintenant actif!");
  }

  // Méthode pour gérer le clic sur le bouton
  handleClick(event) {
    event.preventDefault(); // Empêche le comportement par défaut du lien
    const button = event.currentTarget; // Récupère l'élément cliqué
    const text = button.innerText; // Récupère le texte du bouton

    // Remplace le texte du bouton par "Chargement..."
    button.innerText = "Chargement...";

    // Récupère l'URL de redirection à partir de l'attribut data-url
    const url = button.getAttribute("data-url");

    // Simule une attente de 2 secondes avant de rediriger
    setTimeout(() => {
      window.location.href = url; // Redirection vers la page d'inscription
    }, 2000);
  }
}
