<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        // créer un faux client type navigateur qui va pointer vers une URL

        $client = static::createClient();
        $client->request('GET','/inscription');

        // remplir les champs du formulaire on prend donc les names des inputs 

        $client->submitForm('M\'inscrire', [
        'register_user[email]'=>'julie@gmail.fr',
        'register_user[plainPassword][first]'=>'123456789123',
        'register_user[plainPassword][second]'=>'123456789123',
        'register_user[firstname]'=>'Julie',
        'register_user[lastname]'=>'Doe'
        ]);

        // follow est ce que tu peux suivre les redirections 
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();
       
        // est ce que dans ma page j'ai mon alerte de creation de compte 

        $this->assertSelectorExists('div:contains("Votre compte a été correctement crée . Veuillez vous connecter!")');  



    }
}
