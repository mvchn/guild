<?php

namespace App\Controller\Advertiser;

use App\Form\UserSettingsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     * @Route("/user/settings", name="user_settings")
     *
     * @param Request $request
     * @return Response
     */
    public function homepage(Request $request) : Response
    {
        $userSettings =$this->getUser();
        $form = $this->createForm(UserSettingsFormType::class, $userSettings);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->flush();

            return $this->redirectToRoute('user_settings');
        }

        return $this->render('advertiser/user/settings.html.twig', [
            'form' => $form->createView()
        ]);
    }


}