<?php

namespace App\Controller;

use App\Entity\Order;
use App\Event\OrderEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class OrderController extends AbstractController
{
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

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

    /**
     * @Route("/orders/{uuid}/confirm", methods={"POST"}, name="order_confirm")
     * @ParamConverter("order", class="App:Order")
     */
    public function confirm(Order $order): Response
    {
        $order->setStatus('confirmed');

        $event = new OrderEvent($order);
        $this->eventDispatcher->dispatch($event, OrderEvent::CONFIRMED);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('order_show_uuid', ['uuid' => (string)$order->getUuid()]);
    }
}
