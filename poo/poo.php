<?php

include "vendor/autoload.php";

use App\Classes\Eleve;
use App\Classes\Prof;
use App\Interfaces\AffichableInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('var/logs/toto.log'));

function afficher(AffichableInterface $objet)
{
    $objet->afficher();
}

afficher(new Eleve("Dupond", "Robert", 23));

$eleve1 = new Eleve("Dupont", "Jean", 24);
//$eleve1->afficher();
afficher($eleve1);
$log->info('Creation Eleve 1');

$prof1 = new Prof("Dupond", "Justine", 42);
//$prof1->afficher();
afficher($prof1);
$log->info('Creation Prof 1');

echo "\n\n";


