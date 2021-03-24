<?php

namespace App\Controller\Advertiser;

use App\Entity\OfferLanding;
use App\Entity\Partner;
use App\Entity\PartnerInterface;
use App\Entity\Product;
use App\Form\LandingCreateFormType;
use App\Form\ProductCreateFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class LandingController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     * @Route("/landings", methods={"GET", "POST"}, name="landings_list")
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) : Response
    {
        $user = $this->getUser();

        if(!$user instanceof PartnerInterface) {
            throw new \RuntimeException(sprintf("Entity %s must be implement PartnerInterface", get_class($user)));
        }

        $partner = $user->getPartner();

        if(!$partner instanceof Partner) {
            throw new \RuntimeException(sprintf("Can\'t find partner from user %s", $user->getUsername()));
        }

        $landings = $this->em->getRepository(OfferLanding::class)->findBy([]);

        $landing = new OfferLanding();
        $form = $this->createForm(LandingCreateFormType::class, $landing);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //$product->setPartner($partner);
            $this->em->persist($landing);

            $this->addFlash(
                'primary',
                'Landing added'
            );

            $this->em->flush();

            return $this->redirectToRoute('landings_list');
        }

        return $this->render('advertiser/landing/index.html.twig', [
            'landings' => $landings,
            'form' => $form->createView()
        ]);
    }



}