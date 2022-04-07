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

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public string $imageName;

    #[ORM\Column(type: 'datetime')]
    public \DateTime $date;

    #[ORM\Column(type: 'boolean')]
    public bool $published = true;

    #[ORM\ManyToOne(targetEntity: Tag::class, inversedBy: 'posts')]
    private $tag;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }


}
