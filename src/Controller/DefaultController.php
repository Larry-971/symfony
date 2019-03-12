<?php 



//Spécifie le name space pour dire que c'est dans cette espace que je veux travailler
namespace App\Controller;

//Import de la class new Response (ctrl + alt + i)  --> permet de retourner une reponse
use Symfony\Component\HttpFoundation\Response;



//Class Default Controller
class DefaultController{
    public function index(){
        return new Response("Hello world");
    }
}



