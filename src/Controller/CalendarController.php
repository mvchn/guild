<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar_index")
     */
    public function index() : Response
    {
        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/calendar/{id}/show", name="calendar_show")
     */
    public function show() : Response
    {
        $order = new Order();

        $form = $this->createForm(OrderType::class, $order);

        return $this->render('calendar/show.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'DefaultController',
        ]);
    }
}