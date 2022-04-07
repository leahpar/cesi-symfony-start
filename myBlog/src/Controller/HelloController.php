<?php

namespace App\Controller;

use App\Service\HelloService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{
    /**
     * @Route("/helloworld", name="hello_world")
     */
    public function helloWorld()
    {

        return $this->render("hello/helloworld.html.twig");
    }

    /**
     * @Route("/hello", name="hello_prenom")
     */
    public function helloPrenom(Request $request)
    {
        // /hello?prenom=jean
        $prenom = $request->query->get('prenom');

        $response = new Response();
        $response->setContent("<h1>Hello ".$prenom." !</h1>");
        return $response;
    }


    /**
     * @Route("/hellouser", name="hello_user")
     */
    public function helloUser()
    {
        $user = $this->getUser();

        if ($user) {
            $prenom = $user->getEmail();
        }
        else {
            $prenom = "inconnu";
        }

        $response = new Response();
        $response->setContent("<h1>Hello ".$prenom." !</h1>");
        return $response;
    }

    /**
     * @Route("/hello/{nom}/{age}", name="hello_nom_age")
     */
    public function helloNomAge(Request $request, string $nom, int $age)
    {
        $response = new Response();
        $response->setContent("<h1>Hello ".$nom." !</h1><p>Vous avez ".$age." ans</p>");
        return $response;
    }

    /**
     * @Route("/hello/{nom}", name="hello_nom")
     */
    public function helloNom(Request $request,
                             string $nom,
                             HelloService $helloService,
    ) {
        return $this->render("hello/helloNom.html.twig", [
            'nom' => $helloService->majsucule($nom),
        ]);
    }

}
