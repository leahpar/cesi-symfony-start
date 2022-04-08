INFAL156-PHP - Démarrer avec un Framework : Symfony

mailto: raphael@bacco.fr

Avril 2022

# Symfony

Documentation Symfony :
[https://symfony.com/doc/current/index.html](https://symfony.com/doc/current/index.html)

Cours :
https://github.com/leahpar/cesi-symfony-start

## Prérequis

- [ ] PHP / MySQL
- [ ] Programmation Objet (POO)
  - [ ] Classe, Objet
  - [ ] Héritage, Interface
- [ ] PHP POO
  - [ ] Namespace : `namespace`, `use`...
  - [ ] Autoload : `spl_autoload_register()`
- [ ] Composer
  - [ ] Gestion autoload : `composer dump-autoload`...
  - [ ] Gestion dépendances : `composer require`, `composer install`...


## Composants

**Composants de base**

Kernel = Chef d'orchestre

Request = Objet représentant la requête HTTP du client

Response = Objet représentant la réponse HTTP du serveur

```php
// index.php

// Autoload
require '.../vendor/autoload.php';

// Kernel Symfony
$kernel = new Kernel($_SERVER['APP_ENV'], ...);

// Requête du client
$request = Request::createFromGlobals(); // $_GET, $_POST, $_SERVEUR...

// Traitement de la requête
// Retourne une réponse
$response = $kernel->handle($request);

// Envoie la réponse au client
$response->send();
```

**MVC : Couche Contrôleur**

Controller = Classe/fonction "finale" de traitement de la requête

Router = Sélectionne le contrôleur à appeler en fonction de la requête

Service (Helper) = Classe dédiée à une tâche (gestion des logs, gestion bdd, envoi email, envoi sms, tâches métier...)

**MVC : Couche Modèle**

Entity = Donnée manipulée & persistée

Doctrine = Entity Manager = Manipulation des données / abstraction de la base de données

**MVC : Couche Vue**

Twig = Moteur de template

**Workflow (simplifié)**

```mermaid
flowchart LR
	A((Request))
	C((Response))
	A --> B(Router)
	B --> E(Controller)
	E ---> C
	E <--> services
	subgraph services
	F[(BDD)]
	D{{View}}
	G[Mail]
	I[...]
	end
```

**La "magie" d'un framework**

Annotations

Dependancy Injection

Param Converter

EventHandler (Middleware)


## Installation

### Prérequis

- [x] PHP 8 !!!!!
- [x] Base de données : MySQL / MariaDB / PostgreSQL / SQLite
- [ ] Serveur web : Apache / Nginx
- [ ] PhpMyAdmin ou autre

Bundles :

- XAMPP : https://www.apachefriends.org/
- WAMP : https://www.wampserver.com/
- MAMP : https://www.mamp.info/ ✅

**Composer**

- [x] https://getcomposer.org/download

**Symfony CLI**

- [ ] https://symfony.com/download

### Création d'un projet (Symfony CLI)

**Création structure du projet**
```
symfony new my_project_name --webapp
```

- `--version=xx` installe une version spécifique
  - Symfony 6.0 (php8)
  - Symfony 5.4 (php7)
- `--webapp` installe directement plein de packages utiles pour le web
- `--no-git` Sans initialiser un repo git

```
cd my_project_name
```

**Installation des packages**
```
composer install
```

**Serveur web intégré**
```
symfony server:start -d
```
```
symfony server:stop
```

### Création d'un projet (composer)

**Création structure du projet**
```
composer create-project symfony/website-skeleton my_project_name 5.4.*
```
```
cd my_project_name
```

---

### Exercice

- Installation prérequis, Composer & Symfony
- Création projet Symfony
- démarrer le serveur web
- Afficher la page par défaut de Symfony :

![It works !](https://github.com/leahpar/imgs/raw/master/homepage.jpg)

## Architecture

```
my_project_name/
├── composer.json   # Configuration des packages à installer
├── composer.lock   # Liste des packages installés
| 
├── config/         # configuration du projet
├── .env            # configuration de l'environnement (défaut)
├── .env.local      # configuration de l'environnement (local)
| 
├── bin/            # commandes (console)
| 
├── public/         # Ressources web
│   |── css/
│   |── js/
│   |── ...
│   └── index.php
├── src/            # Sources PHP du projets
│   |── Entity/     # M
│   |── Controller/ # C
│   |── Service/
│   └── ...
├── templates/      # V
| 
├── tests/
├── migrations/
├── translations/
| 
├── var/           # Cache, logs...
└── vendor/        # Packages installés
```

## Configuration

**Configuration de l'environnement :** `.env` et  `.env.local`

Déclaration de variables spécifiques à l'environnement

- Nom de l'environnement
- Identifiants de connexion BDD
- URL serveur SMTP
- Identifiants de connexion SMTP
- ...

```bash
# .env
APP_ENV=
DATABASE_URL=
SMTP_SERVER='smtp.free.fr'
SMTP_PORT=1234
SMTP_USER=
SMTP_PASS=
```
```bash
# .env.local (non commité, en dev)
APP_ENV=dev
DATABASE_URL=mysql://jojo:passw0rd@localhost:3306/mydb
SMTP_SERVER='smtp.fakemail.com'
SMTP_PORT=4444
SMTP_USER=jojo
SMTP_PASS=passw0rd
```
```bash
# .env.local (non commité, en prod)
APP_ENV=prod
DATABASE_URL=mysql://user:MUHMHM324@14.213.32.112:3306/mydb
#SMTP_SERVER='smtp.free.fr'
#SMTP_PORT=1234
SMTP_USER=U22_APPUSER
SMTP_PASS=L2M9NU9ONUCY2
```

**Configuration du projet :** `config/*.yaml`

- Algo de hachage des mots de passe
- Locale par défaut
- Charset pour la connexion à la BDD
- ...

```
config/
├── packages/            # Config de chaque package
│   ├── cache.yaml
│   ├── doctrine.yaml
│   ├── mailer.yaml
│   ├── twig.yaml
│   |── ...
│   ├── dev/             # Config spécifique pour 'dev'
│   │   │── cache.yaml
│   │   └── ...
│   ├── test/
│   └── prod/
└── ...
```

Exemple de fichier de configuration :
```yaml
# config/packages/doctrine.yaml
doctrine:
    dbal:
        connections:
            default:
                url: '%env(DATABASE_URL)%' # Utilise les variables d'env
                charset: utf8
```

## Composants

### Annotations

Les annotations permettent de "configurer" les composants dans les commentaires (ou attributs à partir de php8)

=> Moins de code, plus de lisibilité, plus de maintenabilité.

```php
// PHP 7/8

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
public function adminPage() {...}
```

```php
// PHP 8 => attributs

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
public function adminPage() {...}
```

### Controller

Code "métier" :
- Reçoit la requête du client
- Chargé de retourner une réponse

```php
class BlogController extends AbstractController
{
	public function helloWorld(Request $request)
	{
        $response = new Response();
        $response->setContent("<h1>Hello World!</h1>");
        $response->setStatusCode(200);
        return $response;
	}
}
```

`AbstractController` Indique à Symfony que c'est un contrôleur, et fourni des fonctions utiles :

- `$this->render(...)` Retourne une réponse contenant le rendu d'un template.
- `$this->redirectToRoute(...)`  Retourne une réponse de redirection vers un autre contrôleur
-  `$this->getUser()` Retourne l'utilisateur connecté
- `$this->isGranted(...)` Vérifie les droits de l'utilisateur
- `$this->addFlash(...)` Ajoute un message flash dans la session de l'utilisateur
- ...

### Router

Sélectionne le contrôleur à appeler suivant la requête du client :
- Méthode (GET, POST...)
- url
- paramètres d'url (GET)
- ...

Il s'occupe de la réécriture d'url (url rewriting), pas besoin de `.htaccess` pour ça.

```php
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
	#[Route('/posts', name: 'post_list')]
	public function listPosts(Request $request) {...}

	#[Route('posts/{id}', name: 'post_show', requirements: ['id' => '\d+'])]
	public function showPost(int $id) {...}

	#[Route('posts/{slug}/edit', name: 'post_edit', methods: ['GET', 'POST'])]
	public function editPost(string $slug) {...}
}
```

### Exercice

Créer un 1er contrôleur `HelloController`

- `/helloworld` affiche `Hello World!`
- `/hello?name=jean` affiche `Hello Jean!`  => `$request->query->get('name')`
- `/hello/jean` affiche `Hello Jean!`

==GOTO: §Twig==

### Form

Factoriser/externaliser la gestion des formulaires pour les entités

```php
// src/Form/PostType.php

class PostType extends AbstractType
{
    public function buildForm(...):
    {
	    // Configuration 'automatique' (pas très conseillé)
        $builder
            ->add('title')
            ->add('content')
            ->add('date')
        ;
    }
}
```
>💡Astuce : `php bin/console make:form`

```php
// src/Form/PostType.php

class PostType extends AbstractType
{
    public function buildForm(...):
    {
		// configuration manuelle des champs
        $builder
            ->add('title', TextType::class, [
                'required' => true
            ])
            ->add('content', TextareaType::class, [
                'required' => true, 
                'label' => "Contenu de l'article", 
            ])
            ->add('date', DateType::class, [
                'required' => true, 
                "widget" => "single_text",
            ])
        ;
    }
}
```
Liste des types disponibles :
https://symfony.com/doc/current/reference/forms/types.html

Utilisation dans le contrôleur :
```php
#[Route('/new', name: 'post_new')]
public function formulaire()
{
	// Création formulaire
	$form = $this->createForm(PostType::class);
	// Affichage
    return $this->render('post/new.html.twig', [
        'form' => $form->createView(),
    ]);
}
```

Utilisation dans le template :
```twig
{# On laisse Symfony gérer tout le formulaire #}
{{ form(form) }}
```
```twig
{# Ou on gère un peu à la main #}
{{ form_start(form) }}
    <div>
        {{ form_errors(form) }}
    </div>
    <div>
        {{ form_row(form.title) }}
    </div>
    <div>
        {{ form_row(form.content) }}
    </div>
    <div>
        {{ form_row(form.date) }}
    </div>
{{ form_end(form) }}
```
https://symfony.com/doc/current/form/form_customization.html

![enter image description here](https://github.com/leahpar/imgs/raw/master/form.jpg)

Traiter la soumission du formulaire dans le contrôleur :

**Nouvelle entité**
```php
#[Route('/posts/new', name: 'post_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $em)
{
	// Nouveau Post "vierge"
    $post = new Post();

    // Création formulaire
    $form = $this->createForm(PostType::class, $post);

    // "Remplissage" du formulaire depuis la requête
    $form->handleRequest($request);

	// Si formulaire soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
		
		// ici, $post contient les données soumises

        // On enregistre
        $em->persist($post);
        $em->flush();

        // On redirige vers l'affichage du post par exemple
        return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
    }

    // Si formulaire non soumis OU formulaire invalide
    return $this->render('post/new.html.twig', [
        'form' => $form->createView(),
    ]);
}
```

**Modification d'une entité**
```php
#[Route('/posts/{id}/edit', name: 'post_edit', methods: ['GET', 'POST'])]
public function new(Post $post, Request $request, EntityManagerInterface $em)
{
    // Création formulaire
    $form = $this->createForm(PostType::class, $post);

    // "Remplissage" du formulaire depuis la requête
    $form->handleRequest($request);

	// Si formulaire soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
		
		// ici, $post contient les données soumises

        // On enregistre
        $em->persist($post);
        $em->flush();

        // On redirige vers l'affichage du post par exemple
        return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
    }

    // Si formulaire non soumis OU formulaire invalide
    return $this->render('post/new.html.twig', [
        'post' => $post,
        'form' => $form->createView(),
    ]);
}
```


### Exercice

- Mettre à jour le contrôleur pour utiliser les Form.
- Nouvelle page de modification de l'entité.

>💡Astuce : `php bin/console make:crud` !


### Dependancy Injection

Symfony s'occupe d'instancier (en singleton) les différents services demandés.

```php
namespace App\Service;

class Mailer {
	public function send(string $to, string $subject, string $message)
	{
		// ...
	}
}
```

```php
// Controller
use App\Service\Mailer;

class BlogController extends AbstractController
{
	public function sendEmail(Mailer $mailer)
	{
		// ...
		$mailer->send($to, $subject, $message);
		// ...
	}
}
```
Gestion des dépendances :

```php
class Mailer {
	private SMTPClient $smtp;
	private AntiSpamManager $spamManager;
	
	public function __construct(SMTPClient $smtp, AntiSpamManager $spamManager)
	{
		$this->smtp = $smtp;
		$this->spamManager = $spamManager;
	}
	
	public function send(string $to, string $subject, string $message)
	{
		$email = new Email($to, $subject, $message);
		if ($this->spamManager->check($email)) {
			$this->smtp->send($email);
		}
	}
}
```

## Twig

Moteur de template => simplifie le rendu html (ou autre).

https://twig.symfony.com/doc/3.x/

On "externalise" le rendu de la page dans un template :
`templates/hello/hello-world.html.twig`
```html
<html>
<head>
    <title>Hello World!</title>
</head>
<body>
    <h1>Hello World!</h1>
</body>
</html>
```
```php
#[Route('/helloworld', name: 'hello_world')]
public function helloWorld()
{
    return $this->render('hello/hello-world.html.twig');
}
```

### Syntaxe

Twig = contenu statique (html, json...) + syntaxe basée sur 3 éléments :
-   `{{ ... }}` Affiche quelque chose
-   `{% ... %}` Inscruction (if, else, for...)
-   `{# ... #}` Commentaire

```twig
{# Affiche une variable #}
<span>{{ my_var }}</span>
```
```twig
{# Affiche une variable d'objet : $post->getTitle() #}
{{ post.title }}
```
```twig
{# Conditions #}
{% if my_var < 0 %}
	<strong>my_var est négatif</strong>
{% else %}
	<strong>my_var est positif</strong>
{% endif %}
```
```twig
{# définir une variable #}
{% set my_list = [1, 2, 3] %}
```
```twig
{# boucles #}
<ul>
{% for item in my_list %}
	<li>{{ item }}</<li>
{% endfor %}
</ul>
```
```twig
{# Filtres #}
{{ my_var|lower }}
{{ my_var|upper }}
{{ my_date|date('Y/m/d h:i') }}
...
```

### Paramètres

```php
// Controller
return $this->render('template.html.twig', [
    'my_var' => "toto",
    'post' => $post,
    'my_list' => [1, 2, 3],
    // ...
]);
```

### Héritage

On factorise le code commun à toutes les pages :
```twig
{# templates/base.html.twig #}
<html>
<head>
	<title>{% block title %}{% endblock %}</title>
</head>
<body>
	{% block body %}{% endblock %}
</body>
</html>
```
Notre template se simplifie donc :
```twig
{# templates/hello/hello-world.html.twig #}

{% extends 'base.html.twig' %}

{% block title %}Hello World!{% endblock %}

{% block body %}
    <h1>Hello World!</h1>
{% endblock %}
```
### Exercice

1. Reprendre le `HelloController` en utilisant les templates twig.
- `/helloworld` affiche `Hello World!`
- `/hello?name=jean` affiche `Hello Jean!`
- `/hello/jean` affiche `Hello Jean!`
2. Ajouter une page similaire mais avec une méthode `POST`au lieu de `GET`
- Formulaire html
- `$request->request->get('name')`


## Doctrine

==Prérequis : § Injection de dépendances==

ORM = **O**bject **R**elational **M**apping

Permet l'abstraction de la base de donnée.

On manipule des classes/objets (entités), **pas des tables/lignes**.

**Références annotations doctrine :**
https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/reference/annotations-reference.html

### Entity

```php
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'datetime')]
    private $date;
}
```
**Création de la base de donnée :**

```
php bin/console doctrine:database:create
```
```
Created database `myBlog` for connection named default
```


**Voir les requêtes de migration de la BDD :**
```
php bin/console doctrine:schema:update --dump-sql
```
```
 The following SQL statements will be executed:
     CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;
```

**Exécuter les requêtes de migration de la BDD :**
```
php bin/console doctrine:schema:update --force
```
```
 Updating database schema...
     1 query was executed
 [OK] Database schema updated successfully!
```


### Entity Manager

Référence annotations :
https://doctrine2.readthedocs.io/en/latest/reference/annotations-reference.html

```php
public function listPosts(EntityManagerInterface $em)
{
	$posts = $em->getRepository(Post::class)->findAll();
	// ...
}
public function getPost42(EntityManagerInterface $em)
{
	// Sélection générique
	$posts = $em->getRepository(Post::class)->findBy(['id' => 42]);
	$post = $em->getRepository(Post::class)->findOneBy(['id' => 42]);
	// Sélection par ID
	$post = $em->getRepository(Post::class)->find(42);
	// Sélection "magique" find[One]By<Attribut>($value) :
	$posts = $em->getRepository(Post::class)->findById(42);
	$post = $em->getRepository(Post::class)->findOneById(42);
	// ...
}
public function createPost(EntityManagerInterface $em)
{
	$post = new Post();
	$post->setTitle('Un super titre');
	$post->setContent('Un super contenu');
	$post->setDate(new \DateTime());
	// On indique que l'objet est à "persister" = enregistrer en base
	$em->persist($post);
	// Exécution des requêtes (INSERT, UPDATE, DELETE...)
	$em->flush();
}
```

### Exercice

- Créer une entité
- Exécuter la migration de bdd
- Insérer quelques lignes à la main en bdd
- Créer un nouveau contrôleur
  - Lister les entités : `/posts`
  - Afficher une entité : `/posts/{id}`
  - Créer une entité : `/posts/new` (`POST`)
  - Supprimer une entité : `POST: /posts/{id}/delete`
  - ~~Modifier une entité~~

>💡Astuce : `php bin/console make:entity`

>💡Astuce : `php bin/console make:controller`


### Repository

Quand les fonctions génériques ne suffisent plus..

```php
public function searchPost(Request $request, EntityManagerInterface $em)
{
	$title = $request->query->get('title');
	// Problème ici : on veut une recherche 'LIKE' sur le titre
	$posts = $em->getRepository(Post::class)->findByTitle($title);
	// ...
}
```

```php
#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
	// ...
}
```

```php
class PostRepository extends ServiceEntityRepository
{
    // La fonction générique findByTitle() sera remplacée par cette fonction
    public function findByTitle(string $value)
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.title like :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('p.id', 'ASC')
        ;
        return $query->getQuery()->getResult();
    }
}
```


### Exercice

- Ajouter un champ de recherche sur la page de listing


### Param Converter

Converti automatiquement un paramètre de la requête (GET) en une entité.

```php
#[Route('/{id}', name: 'post_show')]
public function showPost(int $id, EntityManagerInterface $em)
{
	$post = $em->getRepository(Post::class)->findOneBy(
		['id' => $id]
	);
	if ($post == null) {
		return new Response(null, 404);
	}
	return $this->render('post/show.html.twig', [
		'post' => $post
	];
}
```
Simplifié en :
```php
#[Route('/{id}', name: 'post_show')]
public function showPost(Post $post)
{
	return $this->render('post/show.html.twig', [
		'post' => $post
	];
}
```

### Exercice

- Mettre à jour le contrôleur pour utiliser le param converter.


==GOTO: §Form==


## Divers

### Session

```php
public function index(SessionInterface $session)
{
    $session->set('foo', 'bar');
    $foobar = $session->get('foobar', 'default');
}
```

### Messages flash

```php
// Controller
public function index()
{
	$this->addFlash('notice','Yolo!');
	$this->addFlash('error','Pas bien !');
}
```
```twig
{# twig #}
{% for label, messages in app.flashes %}
    {% for message in messages %}
        <div class="flash-{{ label }}">
            {{ message }}
        </div>
    {% endfor %}
{% endfor %}
```

### Emails

https://symfony.com/doc/current/mailer.html

Configurer un serveur SMTP : `.env.local` => `MAILER_DSN=...`

```php
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

public function sendEmail(MailerInterface $mailer) {
    $email = (new Email())
        ->from('hello@example.com')
        ->to('you@example.com')
        ->subject('Time for Symfony Mailer!')
        ->text('Sending emails is fun again!')
        ->html('<p>See Twig integration for better HTML integration!</p>');
    $mailer->send($email);
    // ...
}
```

### HTTP Client

https://symfony.com/doc/current/http_client.html

```php
use Symfony\Contracts\HttpClient\HttpClientInterface;

public function httpCall(HttpClientInterface $client)
{
	$response = $client->request(
            'GET',
            'https://api.github.com/repos/symfony/symfony-docs'
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        // ...
}
```

### Cache

https://symfony.com/doc/current/cache.html

```yaml
# config/packages/cache.yaml
framework:
  cache:
    pools:
      my_cache_pool: # autowireable via "CacheInterface $myCachePool"
        adapter: cache.adapter.filesystem
```

```php
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

public function test(CacheInterface $myCachePool)
{
    $value = $myCachePool->get('my_cache_key', function (ItemInterface $item) {
        $item->expiresAfter(3600);

        $result = rand(1, 1000); // Traitement très long et compliqué

        return $result;
    });

    dump($value);
    //$myCachePool->delete('my_cache_key');

	// ...
}
```
### Authentification

https://symfony.com/doc/current/security.html

### Paramétrage

https://symfony.com/doc/current/configuration.html

```bash
# .env / .env.local
GMAIL_PASSWORD=toto123
```

```yaml
# config/services.yaml
parameters:
  gmail_password: '%env(GMAIL_PASSWORD)'
    images_directory: '%kernel.project_dir%/public/uploads/'
```

```php
// Controller
public function randomPage()
{
	$gmailPasswd = $this->getParameter('gmail_password');
	$imageDir = $this->getParameter('images_directory');
	// ...
}
```


### Upload de fichiers

https://symfony.com/doc/current/controller/upload_file.html

```php
// Entity/Post.php
class Post {
	// ...
	
	#[ORM\Column(type: 'string', length: 255, nullable: true)]
	public string $imageName;

	// ...
}
```

```php
// Form/PostType.php

// ...
->add('imageFile', FileType::class, [
    'label' => 'Image',
    'mapped' => false,
    'required' => false,
    'constraints' => [
        new File([
            'maxSize' => '2M',
            'mimeTypes' => ['image/*'],
        ])
    ],
])
```

```php
// Controller/BlogController.php

// ...
if ($form->isSubmitted() && $form->isValid()) {

    // On récupère l'image uploadée
    /** @var UploadedFile $imageFile */
    $imageFile = $form->get('imageFile')->getData();

    // Si image uploadée
    if ($imageFile) {

        // On lui génère un nom unique
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $originalExtension = pathinfo($imageFile->getClientOriginalName(), PATHINFO_EXTENSION);
        $newFilename = $originalExtension . "-" . uniqid() . "." . $originalExtension;

        try {
            // on "enregistre" le fichier
            $imageFile->move(
                $this->getParameter('images_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            // ...
            $this->addFlash('error', 'Image pas enregistrée');
        }

        // Et on garde le nom de l'image
        $post->imageName = $newFilename;
    }

    // $em->persist($post);
    // $em->flush();
    // return $this->redirectToRoute(...);
}
```

```twig
{# formulaire.html.twig #}
...
{{ form_row(form.imageFile) }}
...
```

```twig
{# post.html.twig #}
...
<img src="/uploads/{{ post.imageName }}">
...
```

### API

```php
#[Route('/api/random', name: 'api_random')]
public function getRandomNumber()
{
    $data = [
        "randomNumber" => rand(1, 1000),
    ];
    return new JsonResponse($data);
 }
```

Sérialisation basique :

```php
#[Route('/api/posts/{id}', name: 'api_post')]
public function getPost(Post $post)
{
    return new JsonResponse($post);
}
```
```json
// /api/posts/19
{
  "id": 19,
  "title": "Mon post N°19",
  "content": "Lorem ipsum etc...",
  "date": "2022-04-05T11:34:21+02:00",
  "published": true
}
```

Sérialization avancée :
```php
// Entity/User.php
use use Symfony\Component\Serializer\Annotation\Ignore;
class User {
	// ...
	#[ignore]
	public string $password;
	// ...
}
```
```php
#[Route('/api/users/{id}', name: 'api_user')]
public function getPost(User $user, SerializerInterface $serializer)
{
    $json = $serializer->serialize($user, 'json');
    return new JsonResponse($json, json: true);
 }
```


### TODO

- [ ] Recherche / filtres

