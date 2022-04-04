<?php

namespace App\Classes;

class Personne {
    public string $nom;
    public string $prenom;
    public int $age = 0;

    public function __construct($nom, $prenom, $age)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->age = $age;
    }


}
