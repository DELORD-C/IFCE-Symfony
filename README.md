# Formation Symfony

## Liens Utiles

Repo Github
> https://github.com/DELORD-C/IFCE-Symfony

Doc Symfony
> https://symfony.com/doc/current/index.html

Doc Twig
> https://twig.symfony.com/doc/

Bin Symfony.exe
> https://drive.google.com/file/d/1Aw037MU00Xu5ptp6V3zQYls0mx8Vgv8f/view?usp=sharing

PHP
> https://www.php.net

Composer
> https://getcomposer.org

Xampp
> https://www.apachefriends.org/download.html

## Architecture des dossiers

```shell=
assets/ //css, js, medias
bin/ //console
config/ //configuration de l'application
migrations/ //historiques des actions de l'ORM sur la base de donnée
node_modules/ //dépendances npm
public/ //racine de notre dossier web
src/ //contient tout le code php, les controllers, entités, repertoires, etc.
templates/ //templates de pages en twig
translations/ //traductions
var/ //cache & logs
vendor/ //dépendances php
```

## Controller & Routes

> Un controller est un objet contenant des méthodes qui correspondent uniquement à chaque route de notre application.

> Par défaut les routes sont définies dans config/routes.yaml, cependant, on aura tendance à plutôt utiliser Annotations qui permet de définir les routes directement au dessus de chaque méthode dans nos contrôlleurs.

```php=
<?php

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RandomController
{
    /**
     * @throws Exception
     */
    #[Route('/random/number/{max}')]
    function number (Int $max = 100) : Response
    {
        $number = random_int(0,$max);

        return new Response($number);
    }
}
``` 

## Twig

Twig est un Moteur de Template, il permet de générer des pages html en y ajoutant de la logique et des variables.

Pour retourner un template dans un Controlleur, il faut utiliser la méthode render(). Celle-ci est accessible uniquement si notre controlleur étend la classe AbstractController

### Exemple de if en Twig
```twig=
{% if number == 0 %}
    There is no apple.
{% elseif number == 1 %}
    There is one apple.
{% else %}
    There is a lot of apples.
{% endif %}
```
### Echappement automatique

#### Annulation d'echappement unique
Dans le fichier .twig on ajoute ***| raw***
```twig=
<html lang="en">
    <body>
        {{ html|raw }}
    </body>
</html>
```

#### Bloc d'annulation d'échappement
```twig=
{% autoescape false %}
    {{html}}
    {{ date.today }}
{% endautoescape %}
```


### Passer des variables à toutes les vues
Dans le twig.yaml, en utilisant `globals` qui est un attribut de `twig`
```yaml=
twig:
    globals:
        date: '@App\Custom\Date'
```
La variable "date" sera connue dans toutes les pages.

### Récupérer l'url d'une route
Dans un twig, possibilité de récupérer l'url d'une route en utilisant path
ex:

```twig=
<a href="{{ path('app_random_million') }}">Million</a>
```

### Inclusion
L'instruction include inclut un modèle et produit le contenu rendu de ce fichier (ici dans le fichier Default.html.twig) :
```twig=
<body>
        {% include '/Parts/_navbar.html.twig' %}
</body>
```


### Héritage
Tout d'abord, il faut créer un fichier twig (ex: default.html.twig) à la racine du dossier templates afin de permettre l'héritage cad la reprise automatique du contenu de ce fichier.
ex de fichier default (ici le titre et le bloc content)
```twig=
    <head>
    <title>Symfony - {% block title %}No title{% endblock %}
    </title>
</head>
<body>
    {% include 'Parts/_navbar.html.twig' %}
    {% block content %}{% endblock %}
</body>
```

Le mécanisme de l'héritage sera en action uniquement si l'on place au début de chaque fichier enfant la référence au fichier parent
```twig=
{% extends 'default.html.twig' %}
```
Une fois l'héritage déclaré dans un enfant, on peut uniquement modifier les blocks du parent.

## Formulaires

Création d'un formulaire comme service (classe Type)

`src/Form/PostType.php`
```php=
<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PostType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class)
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class);
    }
}
```
Utiliser le formulaire dans un controller :
```php=
$post = new Post();
$form = $this->createForm(PostType::class, $post);
```

## Doctrine

### Traitement d'un formulaire et ajout des données en BDD


* Exemple d''insertion en base de données de données fournies via un formulaire
```php-template=
<?php
public function create (Request $request, EntityManagerInterface $em) : Response
{
    $post = new Post();
    $form = $this->createForm(PostType::class, $post);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $post = $form->getData();
        $em->persist($post);
        $em->flush();
        return $this->redirectToRoute('app_post_list');
    }
}
```

### Récupération de la liste des Posts
On récupère la liste du côté du Controller `PostController.php` en passant un `PostRepository` en paramètre de la méthode (Autowiring), puis on passe cette liste à la vue :
```php=
#[Route('/all/')]
function list(PostRepository $postRepository): Response
{
    $posts = $postRepository->findAll();
    return $this->render('Post/list.html.twig', [
        'posts' => $posts
    ]);
}
```
Du côté de la vue, il suffit de faire une boucle pour parcourir la liste des Posts et les afficher :
```twig=
{% for myPost in posts %}
    <tr>
        <th>{{ myPost.id }}</th>
        <td>{{ myPost.subject }}</td>
        <td>{{ myPost.content }}</td>
        <td>{{ myPost.createdAt.format('d/m/y') }}</td>
        <td>
            <a href="{{ path('app_post_view', {'post': myPost.id}) }}">View</a>
            <a href="{{ path('app_post_update', {'post': myPost.id}) }}">Update</a>
            <a href="{{ path('app_post_delete', {'post': myPost.id}) }}">Delete</a>
        </td>
    </tr>
{% endfor %}
```

## Webpack Encore

Webpack Encore est le gestionnaire de CSS, Javascript et médias d'un projet symfony (en les rendant public par exemple)

Installation
```shell=
composer require symfony/webpack-encore-bundle
```

### Relier du CSS et JS à nos templates
Il suffit d'utiliser encore_entry_[type]_tags()
```twig=
<head>
    <title>
        Symfony - {% block title %}No Title{% endblock %}
    </title>
    {{ encore_entry_link_tags('app') }}
</head>
<body>
    {% include 'Parts/_navbar.html.twig' %}
    {% block content %}{% endblock %}
    {{ encore_entry_script_tags('app') }}
</body>
```

### Npm
Télécharger Node.JS https://nodejs.org/en/ pour installer npm

Une fois installé lancer la commande
```shell=
npm -v  #pour verifier que l'installation est OK

npm install #permet d'installer toutes les dépendance front du projet
```

### Bootstrap

Pour installer Bootstrap
```shell=
npm install bootstrap
```

Pour intégrer bootstrap au projet, on doit
l'importer dans notre Entry Point
```javascript=
import 'bootstrap/scss/bootstrap.scss';

require('bootstrap');
```

#### Scss

Décommenter .enableSassLoader() dans webpack.config.js

Installer sass et sass-loader
```shell=
npm install sass sass-loader
```

### Ecouter les modifications et compiler
```shell=
npm run watch
```

## Traductions
Récupére automatiquement les traductions et les importer dans les fichiers yaml :
```shell=
php bin/console translation:extract --force fr --format=yaml
```