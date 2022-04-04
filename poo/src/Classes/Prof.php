<?php

namespace App\Classes;

use App\Interfaces\AffichableInterface;

class Prof extends Personne implements AffichableInterface
{
    public bool $isIntervenant;

    public function afficher()
    {
        echo "je m'appelle ".$this->prenom." et j'ai ".$this->age." ans, je suis un prof\n";
    }
}

