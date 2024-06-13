<?php

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function add(ProductRepository $productRepository,EntityManagerInterface $entityManager,Request $request, $id): Response
    {
    //    recupérer l'objet souhaité 
    $product = $productRepository->findOneById($id);

    // si pdt existant  ajouter le pdt à la whislist
    if ($product) {
        $this->getUser()->addWhislist($product);
        // sav en bdd
        $entityManager->flush();
    }
      $this->addFlash(
        'success',
        "Produit correctement ajouté à votre liste de souhait."
    );

       return $this->redirect($request->headers->get('referer'));

   
    }
  
    #[Route('/compte/liste-de-souhait/remove/{id}', name: 'app_account_whislist_remove')]
    public function remove(ProductRepository $productRepository,EntityManagerInterface $entityManager,Request $request, $id): Response
    {
    //    recupérer l'objet à supprimer
    $product = $productRepository->findOneById($id);

    // si pdt existant  supprimer  le pdt de la whislist
    if ($product) {

        $this->addFlash('success', 'Produit correctement supprimé de votre liste de souhait');

        $this->getUser()->removeWhislist($product);
        // sav en bdd
         $entityManager->flush();

    } else {
        $this->addFlash('danger', 'Produit introuvable');
    }
    
    return $this->redirect($request->headers->get('referer'));

    }

   
}
