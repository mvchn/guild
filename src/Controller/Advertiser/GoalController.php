<?php

namespace App\Controller\Advertiser;

use App\Entity\Attribute;
use App\Entity\Goal;
use App\Entity\Offer;
use App\Form\AttributeFormType;
use App\Form\DeleteEntityFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class GoalController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     * @Route("/products/{productId}/goals/{id}/edit", methods={"GET", "POST"}, name="attributes_edit",  requirements={"id"="\d+"})
     * @ParamConverter("goal",  class="App:Goal")
     *
     */
    public function edit(Goal $goal, Request $request) : Response
    {
        $form = $this->createForm(AttributeFormType::class, $goal);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($goal);

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
     * @Route("/products/{productId}/goals/{id}/show", methods={"GET"}, name="attributes_show",  requirements={"id"="\d+"})
     * @ParamConverter("attribute", class="App:Attribute")
     *
     */
    public function show(Offer $attribute) : Response
    {
        return $this->render('advertiser/offer/show.html.twig', [
            'attribute' => $attribute,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     * @Route("/products/{productId}/goals/{id}/remove", methods={"GET", "POST"}, name="offers_remove",  requirements={"id"="\d+"})
     * @ParamConverter("goal", class="App:Goal")
     * @ParamConverter("product", class="App:Product")
     *
     */
    public function remove(Goal $goal, Request $request) : Response
    {
        $form = $this->createForm(DeleteEntityFormType::class, $goal, ['data_class' => get_class($goal)]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->remove($attribute);

            $this->addFlash(
                'primary',
                'Offer removed'
            );

            $this->em->flush();

            return $this->redirectToRoute('attributes_list');
        }

        return $this->render('advertiser/attribute/show.html.twig', [
            'form' => $form->createView()
        ]);
    }




}