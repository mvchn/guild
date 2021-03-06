<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function dashboard() : Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * @Route("/admin/changelog", methods={"GET"}, name="changelog")
     */
    public function changelog() : Response
    {
        return $this->render('admin/changelog.html.twig');
    }


}