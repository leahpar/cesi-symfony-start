<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public $id;

    #[ORM\Column(type: 'datetime', nullable: true)]
    public $date;

    #[ORM\Column(type: 'string', length: 255)]
    public $state = "panier";

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'paniers')]
    #[ORM\JoinColumn(nullable: false)]
    public $user;

    #[ORM\OneToMany(targetEntity: PanierProduit::class, mappedBy: 'panier')]
    public $panierProduits = [];


}
