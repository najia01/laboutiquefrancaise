<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WhislistController extends AbstractController
{
    #[Route('/compte/liste-de-souhait', name: 'app_account_whislist')]
    public function index(): Response
    {
        return $this->render('account/whislist/index.html.twig');
    }

    #[Route('/compte/liste-de-souhait/add/{id}', name: 'app_account_whislist_add')]
    public function add(): Response
    {
    //    recupérer l'objet souhaité 

    // si pdt existant et util connecté ajouter le pdt à la whislist
    }
}
