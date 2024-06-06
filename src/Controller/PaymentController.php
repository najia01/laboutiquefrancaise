<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    #[Route('/commande/paiement/{id_order}', name: 'app_payment')]
    public function index($id_order, OrderRepository $orderRepository): Response
    {
      Stripe::setApiKey($_ENV ['STRIPE_SECRET_KEY']);
            
      $order = $orderRepository->findOneBy([
        'id'=> $id_order,
        'user'=> $this->getUser()
      ]);

      if (!$order) {
        return $this->redirectToRoute('app_home');
      }

      $products_for_stripe = [];

      foreach ($order->getOrderDetails() as $product)
      {
        $products_for_stripe[] = [
          'price_data' => [
            'currency'=>'eur',
            'unit_amount'=> number_format($product->getProductPricewt() * 100, 0, '', ''),
            'product_data'=>[
                'name'=> $product->getProductName(),
                'images'=>[
                  $_ENV['DOMAIN'] . '/uploads/'. $product->getProductIllustration()
                ]
            ]
          ],
          'quantity' => $product->getProductQuantity(),
        ];
      }

      $products_for_stripe[] = [
        'price_data' => [
          'currency'=>'eur',
          'unit_amount'=> number_format($order->getCarrierPrice() * 100, 0, '', ''),
          'product_data'=>[
              'name'=> 'Transporteur :' . $order->getCarrierName(),
              
          ]
        ],
        'quantity' =>1,
      ];


       
       $checkout_session = Session::create([
        'customer_email'=> $this->getUser()->getEmail(),
        'line_items' => [[
          $products_for_stripe
        ]],
        'mode' => 'payment',
        'success_url' => $_ENV['DOMAIN'] . '/success.html',
        'cancel_url' => $_ENV['DOMAIN']. '/mon-panier/annulation',
      ]);

       
        return $this->redirect($checkout_session->url);
    }
}
