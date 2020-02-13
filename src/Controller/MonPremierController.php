<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonPremierController extends AbstractController
{

    /**
     * @Route("/index", name="index")
     */

    public function index1()
    {
        $message = "<html><head></head><body>bonjour</body></html>";
        $reponse = new Response($message);
        return $reponse;


        /* return $this->render('mon_premier/index.html.twig', [
            'controller_name' => 'MonPremierController',
        ]);*/
    }
    /**
     * @Route("/index2", name="index2")
     */

    public function index2()
    {
        $nom = "lotfi";
        $tab = ["nom1", "nom2", "nom3"];


        return $this->render('mon_premier/index2.html.twig', ['info' => $nom]);
    }
    /**
     * @Route("/mapage", name="mapage")
     */

    public function mapage()
    {

        $nom = "Ben foulen";
        $prenom = "Foulen";

        return $this->render('mon_premier/mapage.html.twig', ['n' => $nom, 'p' => $prenom]);
    }
    /**
     * @Route("/mapage1/{nom}/{prenom}", name="mapage1")
     */

    public function mapage1($nom, $prenom)
    {
        $tab = [1, 2, 3, 4, 5, 6, 7];


        return $this->render('mon_premier/mapage.html.twig', ['n' => $nom, 'p' => $prenom,'tab'=>$tab]);
    }
}
