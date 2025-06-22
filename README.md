# Ez_park – Gestion de Parking (Côté Utilisateur)
Bienvenue dans Ez_PARK, une application web de gestion de parking conçue pour permettre aux utilisateurs de réserver facilement des places de parking en ligne.

🚗 Fonctionnalités principales

- 🔍 Consultation des places disponibles en temps réel
- 📅 Réservation d’une place pour une date et une durée données
- 💳 Paiement sécurisé via PayPal
- 🔄 Mise à jour automatique du statut des places (libre, réservée, occupée)

  
  🧠 Comment ça fonctionne ?
  1.Page de connexion: un utilisateur déjà inscrit utilise ses identifiants pour se connecter sinon il peut créer un compte et se connecter.
  2.Accueil: Après connexion il arrive sur la page d'accueil où il peut voir ses reservations (en cours ou à venir) et son historique de reservation
    un bouton reserver une place qui lui permet d'accéder à un formulaire de reservation.
  3.Formulaire de reservation: Où il peut choisir la place, le type de place (normale, 2roues, handicapée), la date et heure de début ainsi que
  la date et heure de fin après il a le bouton valider.
  4.Paiement: Il est redirigé vers PayPal pour effectuer le paiement.
  5.Confirmation:  une fois le paiement effectuer , la reservation est confirmer.
  6.Mise à jour:
        -après réservation la place a pour statut : réservée
        -à l'heure du début le statut passe à : occupée
        -à l'heure de fin le statut passe à : libre

  🧱 Architecture du projet
Le projet utilise l'architecture MVC en PHP procedural:
-Controller: contient la logique métier
-View: Page HTML affichée
-Model: gestion des données, requêtes SQL

⚙️ Technologies utilisées
-PHP (procédurale)
-JavaScript (module)
-MySQL
-HTML/CSS
-Paypal API
-Bootstrap

🔧 Installation
   git clone https://github.com/votre-utilisateur/Ez_PARK_Users.git
  
