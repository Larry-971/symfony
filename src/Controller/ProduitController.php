<?php

namespace App\Controller;

use App\Entity\Produits;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType; //Import des class des types de mes champs
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; //Import pour $request

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit/add", name="produit_add")
     */
    public function add(Request $request)
    {
        // récupérer le manager de données dans doctrine
        $em = $this->getDoctrine()->getManager();

        /*
        // Instance de la class Produit --> il faut importer la class
        $produit = new Produits();
        // remplir mon produit avec le setter
        (1)$produit->setNom("Huwawai Mate Pro 20");
        (1)$produit->setMarque("Huwawei");
        (1)$produit->setPrix(1150);
        (1)$produit->setImage('public\images\huawai.png');
        (1)$produit->setDescription("Chaque fois que les gens mentionnent le mot pomme, il ne désigne plus un fruit délicieux, riche en fibres et en (1)vitamine C, et vous évite de devoir consulter un médecin; les gens distinguent maintenant le fruit avec l'un des appareils électroniques les plus révolutionnaires au monde, le Apple iPhone 6 Plus. Avec la dernière technologie de smartphone pour le sauvegarder, l'iPhone 6 Plus pourrait très bien être l'un des meilleurs smartphones à ce jour. ");

        */

        //Formulaire pour entrer nos données
        $produit = new Produits();

        //Construction du formulaire (on peut changer le type des champs)
        $form = $this->createFormBuilder($produit)->add("nom", TextType::class, ["label" => "Votre nom"])->add("marque")->add("prix")->add("image")->add("description")->add("Envoyer", SubmitType::class)->getForm();

        //Récupérer les données saisie par l'utilisateur
        $form->handleRequest($request);
        //Vérification de la soumission du formulaire
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($produit);
            $em->flush();

            //redirection vers la liste des produit
            return $this->redirectToRoute("produit_list");

        }

        //Utilise le manager
        // persister en base ==> préparation de la requete SQL de l'insertion dans notre base
        //(1)$em->persist($produit);

        //Permet d'exécuter la requete ==> pousser en base
        //(1)$em->flush();

        return $this->render('produit/add.html.twig', [
            'controller_name' => 'ProduitController', "form_produit" => $form->createView()
        ]);
    }


    /**
     * @Route("/produit/list", name="produit_list")
     */

    // Récupérer les données qui sont en base
    public function list(){
        //Utilisation de Repository car on veut faire une requete SQL
        $repo = $this->getDoctrine()->getRepository(Produits::class);

        //Methode proxy qui liste les données de la base
        $produits = $repo->findAll();

        //Requette créer dans le ProduitsRepository
        $produitPromos = $repo->promo(1190);
        
        return $this->render('produit/list.html.twig', ["produits" => $produits, "promos" => $produitPromos]);
    }


    // Méthode pour afficher mes donnée

    /**
     * @Route("/produit{id}/show", name="produit_show")
     */

    // Récupérer les données qui sont en base
    public function show($id){
        $repo = $this->getDoctrine()->getRepository(Produits::class);
        $produit = $repo->find($id);
        return $this->render("produit/show.html.twig", ["produit" => $produit]);
    }


    // Modifier mes données

    /**
     * @Route("/produit/{id}/update", name="produit_update")
     */

    public function update($id){

        // récupérer le manager de données dans doctrine
        $em = $this->getDoctrine()->getManager();

        // Aller récupérer le produit dont il est question avec le Repository
        $produit = $em->getRepository(Produits::class)->find($id);

        $produit->setMarque('blackberry');
        // Pousser la (requette) en base
        $em->flush($produit);
        // Redirection après mise à jour sur une route choisie
        return $this->redirectToRoute("produit_list");

    }

    //Mise à jour de mes données
    /**
     * @Route("/produit/{id}/editUpdate", name="produit_editUpdate")
     */

    public function editUpdate($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produits::class)->find($id);

        $form = $this->createFormBuilder($produit)
                    ->add("nom")
                    ->add("marque")
                    ->add("prix")
                    ->add("image")
                    ->add("description")
                    ->add("Modifier", SubmitType::class)
                    ->getForm();

        //Envoyer la requette si le bouton est soumis
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($produit);
            $em->flush();
        return $this->redirectToRoute("produit_list");

        }            

        //Retourner le formulaire à la vue
        return $this->render("produit/editUpdate.html.twig", ["form_editUpdate" => $form->createView()]);

    }

    // Supprimer données
    /**
     * @Route("/produit/{id}/delete", name="produit_delete")
     */

    public function delete($id){

        // récupérer le manager de données dans doctrine
        $em = $this->getDoctrine()->getManager();

        // Aller récupérer le produit dont il est question avec le Repository
        $produit = $em->getRepository(Produits::class)->find($id);

        // Requete pour supprimer
        $em->remove($produit);

        $em->flush();
        // Redirection après mise à jour sur une route choisie
        return $this->redirectToRoute("produit_list");

    }


    







}//Fin de la class



















