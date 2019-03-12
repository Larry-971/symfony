<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AutoController extends AbstractController
{
    /**
     * @Route("/auto", name="auto")
     */
    public function index()
    {
        return $this->render('auto/index.html.twig', [
            'controller_name' => 'AutoController',
        ]);
    }


        /**
         * @Route("/exemples", name="exemples")
         */
        public function exemples()
        {
            $titre = "Ma première page";
            //test pour vérifier une condition dans twig
            $admin = false;
            $age = 27;
            $voitures = ["Reanult", "Peugeot", "Fiat", "Ford"];

            //Le tableau me permet de rajouter des variables et de les appeler dans la vues (view)
            return $this->render('auto/exemples.html.twig', 
            [ "titre" => $titre, "test" => $admin, "age" => $age, "voitures" => $voitures
            ]); 
        }

        
        /**
         * @Route("/donnees", name="donnees")
         */
        public function donnees(){
            $voitures = [
                ["Id" => 1, "Marque" => "Renault", "Modele" => "Clio 2", "Pays" => "France", "Photo" => "https://via.placeholder.com/300.png/09f/fff
                C/O https://placeholder.com/"],
                ["Id" => 2, "Marque" => "Seat", "Modele" => "Ibiza", "Pays" => "Espagne", "Photo" => "https://via.placeholder.com/300/09f.png/fff
                C/O https://placeholder.com/"],
                ["Id" => 3, "Marque" => "Volkswagen", "Modele" => "Tiguan", "Pays" => "Allemagne", "Photo" => "https://via.placeholder.com/300/09f/fff.png
                C/O https://placeholder.com/"]
            ];
            return $this->render("auto/donnees.html.twig", ["voitures" => $voitures]);
        }

}

// Anotation pour la route obligatoire
