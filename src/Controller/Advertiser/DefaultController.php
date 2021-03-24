<?php

namespace App\Controller\Advertiser;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     *
     * @param Request $request
     * @return Response
     */
    public function dashboard(Request $request) : Response
    {
        return $this->render('advertiser/dashboard/index.html.twig');
    }


}