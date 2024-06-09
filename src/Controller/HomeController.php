<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        
       $mail = new Mail();
       $mail->send('laboutiquefrench@yopmail.com','Gina Doe','Bonjour test de ma classe mail', 'mon premier email' );

        return $this->render('home/index.html.twig');
    }
}
