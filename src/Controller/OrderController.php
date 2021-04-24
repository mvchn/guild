<?php

namespace App\Controller;

use App\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/orders/{id}", methods={"GET"}, name="order_show", requirements={"id"="\d+"})
     * @Route("/orders/{uuid}", methods={"GET"}, name="order_show_uuid")
     * @ParamConverter("order", class="App:Order")
     */
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }
}
