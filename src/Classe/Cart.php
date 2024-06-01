<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function add($product)
    {

        // on appelle la session de symfony
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        // On ajoute une qté +1
        // Si mon produit est déjà dans mon panier
        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
            ];
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => 1
            ];
        }

        // On crée la session cart
        $session->set('cart', $cart);
    }
    
    public function remove()
    {
       return $this->requestStack->getSession()->remove('cart');
    }

    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }

    public function decrease($id)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        if ($cart[$id]['qty'] > 1) {
            $cart[$id]['qty'] = $cart[$id]['qty']- 1;

        } else {

           unset($cart[$id]);
        }

        $session->set('cart', $cart);
        
    }

    public function fullQuantity()
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        $quantity = 0;

        if (!isset ($cart) ){
            return $quantity;
        }

        foreach($cart as $product){
            $quantity = $quantity + $product['qty'];
        }
        return $quantity;
    }

    public function getTotalWt()
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        $price = 0;

        if (!isset ($cart) ){
            return $price;
        }


        foreach($cart as $product){
            $price = $price + ($product ['object']->getPriceWt()* $product['qty']);
        }
        return $price;
    }
   
}
