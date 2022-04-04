<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    #[Route('/posts', name: 'post_list')]
    public function listPosts(EntityManagerInterface $em)
    {
        $posts = $em->getRepository(Post::class)->findAll();
        return $this->render("posts.html.twig", [
            'posts' => $posts
        ]);
    }

    #[Route('/posts/{id}', name: 'post')]
    public function post(EntityManagerInterface $em, Post $post)
    {
        //$post = $em->getRepository(Post::class)->find($id);

        return $this->render("post.html.twig", [
            'post' => $post
        ]);
    }

    #[Route('/posts/{id}/delete', name: 'post_delete')]
    public function deletePost(EntityManagerInterface $em, Post $post)
    {
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('post_list');
    }

    #[Route('/createpost', name: 'post_create')]
    public function createpost(EntityManagerInterface $em)
    {
        $post = new Post();
        $post->title = "Mon 2e post";
        $post->content = "Lorem ipsum etc...";

        $em->persist($post);
        $em->flush();

        return $this->render("post.html.twig", [
            'post' => $post
        ]);
    }


}
