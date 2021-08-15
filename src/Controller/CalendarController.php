<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar")
     */
    public function calendar() : Response
    {
        return $this->render('default/calendar.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}