# Projet 6 symfony

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/6c21e8db2c054a558a5799d6f99bb49b)](https://www.codacy.com/gh/Esaou/projet6symf/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Esaou/projet6symf&amp;utm_campaign=Badge_Grade)

### **Pré-requis**

- Installer WAMP ou un autre logiciel similaire.
- Installer composer.
- Installer npm.
- Vérifiez que PHP 8 est installé et configuré.
- Installer mailhog pour la fonctionnalité de mailing.

### **Installation**

- Cloner le repository
- Lancer la commande " composer install " à la racine du projet.
- Lancer la commande " npm install ".
- Lancer la commande " npm run build".
- Lancer la commande " php bin/console d:d:c " pour créer la base de données.
- Lancer la commande " php bin/console make:migration ".
- Lancer la commande " php bin/console d:m:m ".
- Commentez la ligne 37 à 40 du fichier FigureListener.php pour permettre le chargement des fixtures.
- Lancer la commande " php bin/console d:f:l " pour alimenter la base de données.
- Décommentez la ligne 37 à 40 du fichier FigureListener.php.

### **Identifiants**

- Email : eric.test@test.com
- Mot de passe : Motdepassergpb1!