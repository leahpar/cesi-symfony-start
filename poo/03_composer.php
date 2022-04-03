<?php

require 'vendor/autoload.php';

use App\Entity\Eleve;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$eleve1 = new Eleve();
$eleve1->nom = "Dupont";
$eleve1->prenom = "Jean";
$eleve1->age = 18;
$eleve1->setNote(15);
$eleve1->afficher();


$log = new Logger('name');
$log->pushHandler(new StreamHandler('logs/my_log.log'));
$log->log('info', 'This is a test');
$log->log('warning', 'This is a warning');
$log->log('error', 'This is an error');
