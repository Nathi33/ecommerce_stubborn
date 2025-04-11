// Import du SCSS principal
import "./styles/app.scss";

// Import JS spécifiques
import "./showAndHiddePassword.js";

// Configuration Stimulus
import { Application } from "@hotwired/stimulus";
import HelloController from "./controllers/hello_controller";

// Démarrage de l'application Stimulus
const application = Application.start();
application.register("hello", HelloController);

// Log de bienvenue
console.log("🎉 Webpack Encore est en place avec Stimulus !");
