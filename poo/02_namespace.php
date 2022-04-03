<?php

spl_autoload_register($autoload);

$autoload = function ($class) {
    var_dump($class);
    die();
    if ($class == "Eleve") {
        include "01_classes.php";
    }
};

$eleve1 = new Eleve();
$eleve1->nom = "Dupont";
$eleve1->prenom = "Jean";
$eleve1->age = 18;
$eleve1->setNote(15);
$eleve1->afficher();
