<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BlogController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index()
    {
        return $this->redirectToRoute('posts_list');
    }

    /**
     * Liste des posts
     */
    #[Route('/posts', name: 'posts_list')]
    public function listPosts(EntityManagerInterface $em)
    {
        $date = new \DateTime("2022-04-01");
        $posts = $em->getRepository(Post::class)->findPostsAfterDate($date);

        return $this->render("posts/posts.html.twig", [
            'posts' => $posts,
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
    #[Security("is_granted('ROLE_ADMIN')")]
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
            // $post->date = new \DateTime();
            // ...

            // On récupère l'image uploadée
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            // Si image uploadée
            if ($imageFile) {

                // On lui génère un nom unique
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $originalExtension = pathinfo($imageFile->getClientOriginalName(), PATHINFO_EXTENSION);
                $newFilename = $originalExtension . "-" . uniqid() . "." . $originalExtension;

                try {
                    // on "enregistre" le fichier
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ...
                    $this->addFlash('error', 'Image pas enregistrée');
                }

                // Et on garde le nom de l'image
                $post->imageName = $newFilename;
            }

            // On enregistre
            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Post enregistré');

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

            $this->addFlash('success', 'Post modifié');

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
    public function deletePost(EntityManagerInterface $em, Post $post, MailerInterface $mailer)
    {
        $id = $post->id;
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Post supprimé');

        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Un post a été supprimé')
            ->text('Le post '.$id.' a été supprimé');
        $mailer->send($email);

        return $this->redirectToRoute('posts_list');
    }

    #[Route('/iss', name:'iss')]
    public function iss(HttpClientInterface $client, CacheInterface $myCachePool)
    {
        $position = $myCachePool->get('iss_position', function (ItemInterface $item) use ($client) {
            $item->expiresAfter(60);
            $response = $client->request(
                'GET',
                'http://api.open-notify.org/iss-now.json'
            );

            //$content = $response->getContent();
            //$data = json_decode($content, true);

            $data = $response->toArray();

            // {"timestamp": 1649323511, "message": "success", "iss_position": {"longitude": "17.3419", "latitude": "29.8667"}}
            return $data['iss_position'];
        });


        return $this->render("iss.html.twig", [
            'position' => $position,
        ]);
    }

    #[Route('/random', name:'random')]
    public function test(CacheInterface $myCachePool)
    {
        $value = $myCachePool->get('my_cache_key', function (ItemInterface $item) {
            $item->expiresAfter(30);
            $result = rand(1, 1000); // Traitement très long et compliqué
            return $result;
        });

        return $this->render("random.html.twig", [
            'nombre' => $value,
        ]);
    }


}
