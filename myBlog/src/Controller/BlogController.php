<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    /**
     * Liste des posts
     */
    #[Route('/posts', name: 'posts_list')]
    public function listPosts(EntityManagerInterface $em)
    {
        $date = new \DateTime("2022-04-01");
        $posts = $em->getRepository(Post::class)->findPostsAfterDate($date);
        return $this->render("posts/posts.html.twig", [
            'posts' => $posts
        ]);
    }

    /**
     * Recherche de posts
     */
    #[Route('/posts/search', name: 'posts_search')]
    public function seachPosts(EntityManagerInterface $em, Request $request)
    {
        // /posts/search?search=xxxxxx
        $str = $request->query->get('search');
        $posts = $em->getRepository(Post::class)->findPostsBySearch($str);
        return $this->render("posts/posts.html.twig", [
            'posts' => $posts
        ]);
    }

    /**
     * Affichage d'un post
     */
    #[Route('/posts/{id}', name: 'post_show', requirements: ['id' => '\d+'])]
    public function post(/*EntityManagerInterface $em, */Post $post)
    {
        //$post = $em->getRepository(Post::class)->find($id);

        return $this->render("posts/post.html.twig", [
            'post' => $post
        ]);
    }

    /**
     * Création d'un post
     */
    #[Route('/posts/new', name: 'post_new')]
    public function new(Request $request, EntityManagerInterface $em)
    {
        // Nouveau Post "vierge"
        $post = new Post();

        // Création formulaire
        $form = $this->createForm(PostType::class, $post);

        // "Remplissage" du formulaire depuis la requête
        $form->handleRequest($request);

        // Si formulaire soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // ici, $post contient les données soumises

            // On enregistre
            $em->persist($post);
            $em->flush();

            // On redirige vers l'affichage du post par exemple
            return $this->redirectToRoute('post_show', ['id' => $post->id]);
        }

        // Si formulaire non soumis OU formulaire invalide
        return $this->render('posts/newPost.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Modification d'un post
     */
    #[Route('/posts/{id}/edit', name: 'post_edit')]
    public function edit(Post $post, Request $request, EntityManagerInterface $em)
    {
        // Création formulaire
        $form = $this->createForm(PostType::class, $post);

        // "Remplissage" du formulaire depuis la requête
        $form->handleRequest($request);

        // Si formulaire soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // ici, $post contient les données soumises

            // On enregistre
            $em->persist($post);
            $em->flush();

            // On redirige vers l'affichage du post par exemple
            return $this->redirectToRoute('post_show', ['id' => $post->id]);
        }

        // Si formulaire non soumis OU formulaire invalide
        return $this->render('posts/newPost.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Suppression d'un post
     */
    #[Route('/posts/{id}/delete', name: 'post_delete')]
    public function deletePost(EntityManagerInterface $em, Post $post)
    {
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('posts_list');
    }

}
