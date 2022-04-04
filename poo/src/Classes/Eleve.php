<?php

namespace App\Classes;

use App\Interfaces\AffichableInterface;

class Eleve extends Personne implements AffichableInterface
{
    public string $classe;

    public function afficher()
    {
        echo "je m'appelle ".$this->prenom." et j'ai ".$this->age." ans, je suis un élève\n";
    }

}
