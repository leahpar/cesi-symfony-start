<?php

namespace App\Entity;

class Eleve {

    public string $nom;
    public string $prenom;
    public int $age;

    private int $note;

    public function afficher() {
        echo "Bonjour, je m'appelle $this->prenom $this->nom et j'ai $this->age ans.\n";
        echo "Ma note est $this->note.\n";
    }

    public function setNote(int $note) {
        $this->note = $note;
    }

}

//$eleve1 = new Eleve();
//$eleve1->nom = "Dupont";
//$eleve1->prenom = "Jean";
//$eleve1->age = 18;
//$eleve1->setNote(15);
//$eleve1->afficher();
//
//$eleve2 = new Eleve();
//$eleve2->nom = "Durand";
//$eleve2->prenom = "Paul";
//$eleve2->age = 20;
//$eleve2->setNote(12);
//$eleve2->afficher();


