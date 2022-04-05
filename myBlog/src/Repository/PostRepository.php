<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }


    public function findPostsAfterDate(?\DateTime $date = null)
    {
        // SELECT * FROM post p
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.published = :pub')
            ->setParameter(':pub', true)
            ->orderBy('p.date', 'DESC');

        if ($date != null) {
            // WHERE p.date > :val
            $query->andWhere('p.date > :val')
                ->setParameter(':val', $date);
        }

        return $query->getQuery()->getResult();
    }

    public function findPostsBySearch(string $str)
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.title like :str OR p.content like :str')
            ->setParameter(':str', '%'.$str.'%')
            ->orderBy('p.date', 'DESC');

        // SELECT * FROM Post p
        // WHERE p.title like '%:str%'
        // OR    p.content like '%:str%'
        // ORDER BY p.date DESC

        return $query->getQuery()->getResult();
    }



}
