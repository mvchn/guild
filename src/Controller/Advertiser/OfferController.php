<?php

namespace App\Controller\Advertiser;

use App\Entity\Click;
use App\Entity\Offer;
use App\Entity\Partner;
use App\Entity\PartnerInterface;
use App\Form\DeleteEntityFormType;
use App\Form\OfferCreateFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class OfferController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     * @Route("/offers", methods={"GET", "POST"}, name="offers_list")
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

        $offers = $this->em->getRepository(Offer::class)->findBy([]);

        $offer = new Offer();
        $form = $this->createForm(OfferCreateFormType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //$offer->setPartner($partner);
            $this->em->persist($offer);

            $this->addFlash(
                'primary',
                'Offer added'
            );

            $this->em->flush();

            return $this->redirectToRoute('offers_list');
        }

        return $this->render('advertiser/offer/index.html.twig', [
            'form' => $form->createView(),
            'offers' => $offers
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     * @Route("/offers/{id}/edit", methods={"GET", "POST"}, name="offers_edit",  requirements={"id"="\d+"})
     * @ParamConverter("offer",  class="App:Offer")
     *
     */
    public function edit(Offer $offer, Request $request) : Response
    {
        $form = $this->createForm(OfferCreateFormType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($offer);

            $this->addFlash(
                'primary',
                'Offer changed'
            );

            $this->em->flush();

            return $this->redirectToRoute('offers_list');
        }

        return $this->render('advertiser/offer/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     * @Route("/offers/{id}/show", methods={"GET"}, name="offers_show",  requirements={"id"="\d+"})
     * @ParamConverter("offer", class="App:Offer")
     *
     */
    public function show(Offer $offer) : Response
    {
        return $this->render('advertiser/offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     * @Route("/offers/{id}/remove", methods={"GET", "POST"}, name="offers_remove",  requirements={"id"="\d+"})
     * @ParamConverter("offer", class="App:Offer")
     *
     */
    public function remove(Offer $offer, Request $request) : Response
    {
        $form = $this->createForm(DeleteEntityFormType::class, $offer, ['data_class' => get_class($offer)]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->remove($offer);

            $this->addFlash(
                'primary',
                'Offer removed'
            );

            $this->em->flush();

            return $this->redirectToRoute('offers_list');
        }

        return $this->render('advertiser/offer/show.html.twig', [
            'form' => $form->createView()
        ]);
    }


}