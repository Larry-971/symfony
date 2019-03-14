<?php

namespace App\Controller;

use App\Entity\Produits;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BackProduitController extends AbstractController
{
    /**
     * @Route("/back/produit", name="back_produit")
     */
    public function index()
    {
        //Utilisation de Repository car on veut faire une requete SQL
        $repo = $this->getDoctrine()->getRepository(Produits::class);

        //Methode proxy qui liste les données de la base
        $produits = $repo->findAll();

        return $this->render('back_produit/index.html.twig', [
            "tabProduits" => $produits
        ]);
    }


    //AJOUTER DES DONNEES 
    /**
     * @Route("/back/produit/add", name="back_produit_add")
     */
    public function add(Request $request)
    {
        // récupérer le manager de données dans doctrine
        $em = $this->getDoctrine()->getManager();

        //Formulaire pour entrer nos données
        $produit = new Produits();

        //Construction du formulaire (on peut changer le type des champs)
        $form = $this->createFormBuilder($produit)
                        ->add("nom", TextType::class, ["label" => "Votre nom"])
                        ->add("marque")
                        ->add("prix")
                        ->add("image", FileType::class)
                        ->add("description")
                        ->add("Envoyer", SubmitType::class)
                        ->getForm();

        //Récupérer les données saisie par l'utilisateur
        $form->handleRequest($request);
        //Vérification de la soumission du formulaire
        if($form->isSubmitted() && $form->isValid()){

            //Pour le telechargement de fichier 
            $file = $form->get("image")->getData(); //Nom de la propriété à exploiter
                //Récupérer le nom du fichier
            $fileName = $this->generateUniqueFileName().'.'. $file->guessExtension();
                //Déplace le nom du fichier dans notre dossier
            $file->move($this->getParameter("uploads", $fileName));
            $produit->setImage($fileName);

            $em->persist($produit);
            $em->flush();

            //Message flash pour notification
            $this->addFlash('success', "Produit ajouté avec succès !");

            //redirection vers la liste des produit
            return $this->redirectToRoute("back_produit");

        }

        return $this->render('back_produit/add.html.twig', [
            'controller_name' => 'ProduitController', "form_produit" => $form->createView()
        ]);
    }

    //Fonction pour crypté les fichiers télécharger
    public function generateUniqueFileName(){
        return md5(uniqid());
    }

    //MODIFICATION D UNE DONNEE 

     /**
     * @Route("/back/produit/{id}/editUpdate", name="back_produit_editUpdate")
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
        return $this->redirectToRoute("back_produit");

        }            

        //Retourner le formulaire à la vue
        return $this->render("back_produit/editUpdate.html.twig", ["form_editUpdate" => $form->createView()]);

    }


    //SUPPRESSION D UNE DONNEE
    /**
     * @Route("/back/produit/{id}/delete", name="back_produit_delete")
     */

    public function delete($id){

        // récupérer le manager de données dans doctrine
        $em = $this->getDoctrine()->getManager();

        // Aller récupérer le produit dont il est question avec le Repository
        $produit = $em->getRepository(Produits::class)->find($id);

        // Requete pour supprimer
        $em->remove($produit);

        $em->flush();

        //Message flash pour notification
        $this->addFlash('suppression', "Produit supprimé avec succès !");

        // Redirection après mise à jour sur une route choisie
        return $this->redirectToRoute("back_produit");

    }











}//Fin de la class
