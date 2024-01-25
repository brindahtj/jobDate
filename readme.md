# Projet JOB BOARD

## Description

Dans ce projet, nous avons créé un site web de type job board. Ce site permet aux utilisateurs de consulter des offres d'emploi et de postuler à ces offres. Les utilisateurs peuvent également créer un compte et publier des offres d'emploi.

## Instructions

Dans ce projet, nous allons développer le site fonctionnalité par fonctionnalité. Chaque fonctionnalité sera développée dans une branche séparée. Une fois la fonctionnalité terminée, nous fusionnerons la branche avec la branche principale.

## Fonctionnalités

- [x] En tant qu'utilisateur, je veux pouvoir créer un compte sur le site afin de pouvoir me connecter et postuler à des offres d'emploi.
- [x] En tant qu'entreprise, je veux pouvoir créer un compte sur le site afin de pouvoir me connecter et publier des offres d'emploi et consulter les candidatures.
- [x] Les offres doivent être publiques par ordre décroissant de date de publication.
- [x] Les utilisateurs peuvent filtrer les offres par catégorie et par type de contrat.
- [x] L'administrateur peut créer des catégories et des types de contrat.Il peut également supprimer des offres d'emploi et desactivé des utilisateurs et des entreprises.

## Partie 1

- [x] Dans un premier temps , nous allons faire la partie front-end du site web.

  - Mise en place de la structure HTML et CSS du site web.
  - Mise en place de la page d'accueil(Home page).Avec la navbar et le footer.
  - Mise en place de la page de connexion et d'inscription.
  - Affichage des offres d'emploi sur le page d'accueil.
  - Affichage des entreprises sur le page d'accueil.

- [x] Dans un deuxième temps, nous allons créer le controller du compte utilisateur.

  - Création du controller `accountController`
    `symfony console make:controller AccountController`

- [x] Dans un troisième temps, nous allons faire la partie inscription et connexion

  - Création du formulaire d'inscription et de connexion.
    `symfony console make:user`

- [x] Mise à jour de l'entité User
      `symfony console make:entity`

  - User
  - id (int)
  - email (string)
  - password (string)
  - role (string)
    `J'ai ajouté les champs suivants dans l'entité User`
  - status (string)=>(user,entreprise)
  - username (string)
  - createdAt (date)
    `symfony console doctrine:databse:create`
    `symfony console make:migration`
    `symfony console doctrine:migrations:migrate`

- [x] Création du formulaire d'inscription.
      `symfony console make:registration-form`

- [x] Création du formulaire de connexion.
      `symfony console make:auth`

  - Sécurisation des routes. Les utilisateurs non connectés ne peuvent pas accéder à la page account.

- [x] Dans un quatrième temps, nous allons gérer l'envoi de mail de confirmation de creation de compte. Nous allons utiliser mailtrap.io

## Partie 2

Dans cette partie, nous allons créer la partie back-end du site web.

- [x] Dans un premier temps, nous allons installer le bundle `easyadmin` pour créer un back-office.
      `composer require easycorp/easyadmin-bundle`
      `symfony console make:admin:dashboard`
      `symfony console make:admin:crud`

- [x] Dans un deuxième temps, nous allons créer une entité `HomeSetting` pour gérer les paramètres de la page d'accueil(l'image, le message et le call to action)

  - Création de l'entité `HomeSetting` qui contient les champs suivants: - id (int) - message (string) - image (string) - callToAction (string) - createdAt (datetimeimmutable)
    `symfony console make:entity HomeSetting`

- [x] Dans un troisième temps, nous allons faire le admin crud sur l'entity `HomeSetting` afin de pouvoir modifier les paramètres de la page d'accueil. et rendre la page d'accueil dynamique dans le `homeController`.
  - Création du admin crud sur l'entity `HomeSetting`
    `symfony console make:admin:crud`
  - Modification du `homeController` pour rendre la page d'accueil dynamique à partir des paramètres de la base de données.

## Ce JOUR

- [x] Dans un quatrième temps, nous allons créer une entité `ContractType` pour gérer les types de contrat.Les différents types de contrat sont: CDI, CDD, Freelance, Stage, Alternance, Intérim,Autre.

  - Création de l'entité `ContractType` qui contient les champs suivants: - id (int) - name (string) - slug (string) - createdAt (datetimeimmutable)
    `symfony console make:entity ContractType`

  - Creation du crud admin sur l'entity `ContractType`
    `symfony console make:admin:crud`
    Ajout des ContratTypes dans la base de données par l'admin.

## Partie 3

- [x] Dans un premier temps, nous allons mettre à jour le `accountController` avec un sidebar qui contient un menu qui sera différent selon le status de l'utilisateur.

  - Un candidat aura un menu avec les liens suivants:
    - Mes candidatures
    - Mes informations
    - Profil
  - Un recruiteur aura un menu avec les liens suivants:
    - Mes offres d'emploi
    - Mes informations
    - Les Mot clés
    - Profil

- [x] Dans un deuxieme temps, nous allons créer une `UserProfil`
      qui va permettre à l'utilisateur de modifier ses informations personnelles.Cette entité aura les champs suivants: - id (int) - firstName (string) - lastName (string) - slug (string) - address (string) - city (string) - zipCode (string) - country (string) - phoneNumber (string) - jobSought(string) => poste recherché ==>nullable - presentation (text) - availability (boolean) => disponibilité - website (string) - picture (string) - user (User) => OneToOne
      `symfony console make:entity UserProfil`

- [x] Création du controller et du formulaire d'ajout de `UserProfil` par l'utilisateur.
      `symfony console make:form`

- [x] Création du controller `UserProfilController` qui va gérer les informations de l'utilisateur.
      `symfony console make:controller UserProfilController`

- [x] Créer la route `user/profil/{slug}` qui affiche le profil de l'utilisateur.
- [x] Créer la route `user/profil/{slug}/edit` qui permet à l'utilisateur de modifier son profil.
- [x] Créer la route `user/profil/{slug}/delete` qui permet à l'utilisateur de supprimer son profil.

- [x] Dans un quatrième temps, nous allons créer une entité `EntrepriseProfil` qui contient les informations de l'entreprise.

  - Création de l'entité `EntrepriseProfil` qui contient les champs suivants: - id (int) - name (string) - slug (string) - address (string) - city (string) - zipCode (string) - country (string) - phoneNumber (string) - activityArea (string) => secteur d'activité - email (string) - description (text) - logo (string) - website (string) - user (User) => OneToOne
    `symfony console make:entity EntrepriseProfil`

- Créer la route `entreprise/profil/{slug}` qui affiche le profil de l'entreprise.

- [x] Créer la route `entreprise/profil/{slug}/edit` qui permet à l'utilisateur de modifier son profil.

- [x] Créer la route `entreprise/profil/{slug}/delete` qui permet à l'utilisateur de supprimer son profil.

- [x] Dans un troisieme temps, nous allons créer une entité `Tag` qui sera un ensemble de mots clés qui permettront de décrire les offres d'emploi.

  - Création de l'entité `Tag` qui contient les champs suivants: - id (int) - name (string) - slug (string)
    `symfony console make:entity Tag`

  - Installer cocur/slugify pour générer le slug.
    `composer require cocur/slugify`

  - Créatin du formulaire d'ajout de tag par l'employeur.
    `symfony console make:form`

- Créer la route `/Tag` qui affiche la liste des tags disponibles sur le site sur le profil de l'entreprise et qui inclut un formulaire d'ajout de tag.(Seul l'admin peut supprimer des tags)

  - Faire le admin crud sur l'entity `Tag` (Seul l'admin peut supprimer des tags)
    `symfony console make:admin:crud`

### LA SUITE

## Partie 4

- [x] Dans un premier temps, nous allons créer un controller `OfferController` qui va gérer les offres d'emploi.

  - Création du controller `OfferController`
    `symfony console make:controller OfferController`

- [x] Dans un deuxième temps, nous allons créer une entité `Offer` qui contient les offres d'emploi.

  - Création de l'entité `Offer` qui contient les champs suivants: - id (int) - title (string) - slug (string) - shortDescription (string) - content (text) - createdAt (datetimeimmutable) - salary (int) - location (string) - contractType (ContractType) => ManyToOne - entreprise (EntrepriseProfil) => ManyToOne - tags (Tag) => ManyToMany - isActive (boolean)
    `symfony console make:entity Offer`

  - Création du formulaire d'ajout d'offre d'emploi par l'employeur.
    `symfony console make:form`

  - Création de la route `/entreprise/offer/new` qui permet à l'employeur de créer une offre d'emploi.
  - Création de la route `/entreprise/offer/` qui permet à l'employeur de voir ses offres d'emploi.

  - Création du crud admin sur l'entity `Offer`. L'admin peut desactiver une offre d'emploi.
    `symfony console make:admin:crud`

## Partie 5

- [x] Installation de `orm-fixtures` pour générer des données de test.
      `composer require orm-fixtures --dev`

- [x] Création des fixtures pour les entités `ContractType`, `Tag`, `User`, , `UserProfil`, `EntrepriseProfil`, `Offer`.

- [x] Nous allons, nous allons afficher les 6 dernières offres d'emploi sur la page d'accueil et les 4 dernières entreprises.
- [x] Création de la route `/offre-emploi` qui affiche la liste des offres d'emploi sur la page Offres.Pour cela, nous allons créer une méthode `public function getOffers()` dans le `HomeController` qui va récupérer les offres d'emploi et les passer à la vue `home/offer_list.html.twig`.

- [x] Création de la pagination sur la page `/offre-emploi`. Nous allons le faire sans utiliser de bundle externe.Vous pouvez utiliser le bundle `knplabs/knp-paginator-bundle` si vous le souhaitez.

- [x] Création de la route `/offre-emploi/{id}` qui affiche le détail d'une offre d'emploi sur la page Offre. Pour cela, nous allons créer une méthode `public function getOneOffer()` dans le `HomeController` qui va récupérer l'offre d'emploi et la passer à la vue `home/offer_detail.html.twig`.
- [x] Permettre à l'utilisateur de postuler à une offre d'emploi.

- [x] Création de l'entité `application` qui contient les champs suivants: - id (int) - status (string) => (pending,accepted,refused) - createdAt (datetimeimmutable) - User (User) => ManyToOne car un utilisateur peut postuler à plusieurs offres d'emploi. - Offer (Offer) => ManyToOne car une offre d'emploi peut avoir plusieurs candidatures. - Entreprise (Entreprise) => ManyToOne car une entreprise peut avoir plusieurs candidatures. - message (text) nullable

  `symfony console make:entity Application`

- [x] Création du formulaire d'application à une offre d'emploi.
- [x] Création de la route `/apply/{id}` qui permet à l'utilisateur de postuler à une offre d'emploi.L'utilisateur doit être connecté pour pouvoir postuler à une offre d'emploi.
- [x] Création de la route `/account/application` qui permet à l'utilisateur de voir ses candidatures.
- [x] Création de la route `/entreprise/application` qui permet à l'entreprise de voir les candidatures à ses offres d'emploi.

### PRE PROD

1. Création des différentes pages d'erreur 404, 403, 500 etc...
2. Compiler les assets `php bin/console asset-map:compile`
3. Création du fichier .htaccess `composer require symfony/apache-pack`
4. Création du fichier .env.local et ajout de la variable d'environnement APP_ENV=prod

### PROD

1. Création de la base de données sur le serveur de production.
2. Exporter la base de données de développement et l'importer sur le serveur de production.
3. Modifier le fichier .env.local sur le serveur de production.
4. Se connecter au serveur de production en ssh. exemple: `ssh MonID@SERVER_IP` je valide avec mon mot de passe.
5. Se rendre dans le dossier du projet sur le serveur de production.
6. Se positionner sur le dossier du projey en local et faire `scp votre_fichier.zip MonID@SERVER_IP:/DOSSIER_DU_PROJET`
7. Se rendre dans le dossier du projet sur le serveur de production et faire `unzip votre_fichier.zip`
8. Pointer le domaine vers le dossier public du projet.

### HEROKU

1. Créer un compte sur heroku.com
2. Installer le cli heroku sur votre machine locale ou déployer depuis github.

## AVEC LA CLI HEROKU

- Se connecter à Heroku en ligne de commande
  `heroku login`
- Créer une application sur Heroku
  `heroku create`
- Déployer l'application sur Heroku
  `git add .`
  `git commit -m "first commit"`
  `git push heroku master`
- Créer la base de données sur Heroku dans la partie ressources et choisir le plan gratuit.(JawsDB MySQL ou ClearDB MySQL - Free)
- Allez dans la partie settings et cliquer sur `Reveal Config Vars` et ajouter les variables d'environnement.(APP_ENV=prod, DATABASE_URL=...). Pour DATABASE_URL, vous pouvez récupérer l'url de la base de données dans la partie ressources ou dans la partie settings dans la partie Config Vars changer le nom \_URL par DATABASE_URL.
- Créer le fichier procfile à la racine du projet et ajouter la ligne suivante: `web: heroku-php-apache2 public/`
- J'ajoute dans le fichier `composer.json` les scripts suivants:
  `"compile": [
      "php bin/console cache:clear --env=prod",
      "php bin/console doctrine:migrations:migrate --no-interaction",
  ],`

- Déplacer la ligne `"doctrine/doctrine-fixtures-bundle": "^3.5"` qui se trouve dans require dev dans require.

-Allez dans `/config/bundles.php` et mettre la ligne suivante en commentaire:
`Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['all' => true],`

- Faire un commit et un push sur heroku
  `git add .`
  `git commit -m "first commit"`
  `git push heroku master`

### REFACTORING

Ici nous allons faire du refactoring sur le code existant.
On va ajouter `ck editor`, `select2` et `knplabs/knp-time-bundlebootstrap` pour gérer les dates et les heures.

Les LifeCycle Callbacks permettent d'exécuter du code à des moments précis du cycle de vie d'une entité. Par exemple, nous pouvons utiliser les LifeCycle Callbacks pour générer le slug d'une entité avant de la persister en base de données ou pour mettre à jour la date de création ou de modification d'une entité.
Pour cela, nous allons utiliser les annotations `@ORM\HasLifecycleCallbacks` et `@ORM\PrePersist` et `@ORM\PreUpdate`.
Cette annotation permet d'indiquer à Doctrine que l'entité contient des LifeCycle Callbacks qui seront exécutés à des moments précis du cycle de vie de l'entité.
Ceci va nous permettre de générer le slug et le createdAt et updatedAt automatiquement.
Pour cela nous allons ajouter une méthode ` public function initalizeSlug()` dans l'entité `Offer` qui va générer le slug à partir du titre de l'offre d'emploi.
Nous allons également ajouter une méthode `public function initalizeCreatedAt()` qui va générer la date de création de l'offre d'emploi.
Mettre ceci en place sur toutes les entités qui ont besoin de ces fonctionnalités.
