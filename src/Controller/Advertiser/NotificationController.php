<?php

namespace App\Controller\Advertiser;

use App\Entity\UserNotification;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     *
     * @param Request $request
     * @return Response
     * @Route("/notifications", name="user_notifications_list")
     */
    public function dashboard(Request $request) : Response
    {
        $notifications = $this->em->getRepository(UserNotification::class)->findAll();

        return $this->render('advertiser/notification/index.html.twig', [
            'notifications' => $notifications
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     *
     * @param Request $request
     * @return Response
     * @Route("/notifications/clear", name="user_notifications_clear")
     */
    public function clear(Request $request) : Response
    {
        $notifications = $this->em->getRepository(UserNotification::class)->findAll();

        array_map([$this->em->getRepository(UserNotification::class), 'setRead'], $notifications);

        $this->addFlash(
            'primary',
            'Notifications is clean'
        );

        $this->em->flush();

        return $this->redirectToRoute('advertizer_dashboard');
    }


}