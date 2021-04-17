<?php

namespace App\Controller\Advertiser;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function dashboard(Request $request) : Response
    {
        return $this->render('adm/dashboard.html.twig');
    }

    /**
     * @Route("/changelog", methods={"GET"}, name="changelog")
     */
    public function changelog(Request $request) : Response
    {
        return $this->render('adm/changelog.html.twig');
    }


}