<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    public string $title;

    #[ORM\Column(type: 'text')]
    public string $content;

    #[ORM\Column(type: 'datetime')]
    public \DateTime $date;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

}
