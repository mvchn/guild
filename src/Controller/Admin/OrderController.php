<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/orders", name="admin_orders_")
 * @IsGranted("ROLE_USER")
 */

class OrderController extends AbstractController
{
    /**
     * @Route("", methods={"GET"}, name="list")
     *
     */
    public function list() : Response
    {
        $orders = $this->getDoctrine()->getManager()->getRepository(Order::class)->findAll();

        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders
        ]);

    }

    /**
     * @Route("/{id}", methods={"GET"}, name="show",  requirements={"id"="\d+"})
     * @ParamConverter("order", class="App:Order")
     *
     */
    public function show(Order $order) : Response
    {
        return $this->render('admin/order/show.html.twig', [
            'order' => $order,
        ]);
    }

}