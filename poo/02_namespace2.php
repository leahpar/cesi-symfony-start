<?php

class Eleve {

    public string $nom;
    public string $prenom;
    public int $age;

    public function afficher() {
        echo "Yo, j'suis' $this->prenom $this->nom, $this->age ans.\n";
    }

}
