<?php

namespace App\Controller\Advertiser;

use App\Entity\Attribute;
use App\Entity\Goal;
use App\Entity\Partner;
use App\Entity\PartnerInterface;
use App\Entity\Product;
use App\Form\AttributeFormType;
use App\Form\GoalFormType;
use App\Form\ProductCreateFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProductController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     * @Route("/products", methods={"GET", "POST"}, name="products_list")
     *
     */
    public function list(Request $request) : Response
    {
        $user = $this->getUser();

        if(!$user instanceof PartnerInterface) {
            throw new \RuntimeException(sprintf("Entity %s must be implement PartnerInterface", get_class($user)));
        }

        $partner = $user->getPartner();

        if(!$partner instanceof Partner) {
            throw new \RuntimeException(sprintf("Can\'t find partner from user %s", $user->getUsername()));
        }

        $products = $this->em->getRepository(Product::class)->findBy([]);

        $product = new Product();
        $form = $this->createForm(ProductCreateFormType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $product->setPartner($partner);
            $this->em->persist($product);

            $this->addFlash(
                'primary',
                'Product added'
            );

            $this->em->flush();

            return $this->redirectToRoute('products_list');
        }

        return $this->render('advertiser/product/index.html.twig', [
            'form' => $form->createView(),
            'products' => $products
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("ROLE_CABINET")
     * @Route("/products/{id}/edit", methods={"GET", "POST"}, name="products_edit",  requirements={"id"="\d+"})
     * @ParamConverter("product", class="App:Product")
     *
     */
    public function edit(Product $product, Request $request) : Response
    {
        $editForm = $this->createForm(ProductCreateFormType::class, $product);

        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->em->persist($product);

            $this->addFlash(
                'primary',
                'Product changed'
            );

            $this->em->flush();

            return $this->redirectToRoute('products_list');
        }

        $attribute = new Attribute();
        $attributeForm = $this->createForm(AttributeFormType::class, $attribute);

        $attributeForm->handleRequest($request);
        if ($attributeForm->isSubmitted() && $attributeForm->isValid()) {

            $attribute->setProduct($product);
            $this->em->persist($attribute);

            $this->addFlash(
                'primary',
                'Product changed'
            );

            $this->em->flush();

            return $this->redirectToRoute('products_edit', ['id' => $product->getId()]);
        }

        $goal = new Goal();
        $goalForm = $this->createForm(GoalFormType::class, $goal);

        $goalForm->handleRequest($request);
        if ($goalForm->isSubmitted() && $attributeForm->isValid()) {

            $goal->setProduct($product);
            $this->em->persist($attribute);

            $this->addFlash(
                'primary',
                'Product changed'
            );

            $this->em->flush();

            return $this->redirectToRoute('products_edit', ['id' => $product->getId()]);
        }

        return $this->render('advertiser/product/edit.html.twig', [
            'product' => $product,
            'editForm' => $editForm->createView(),
            'attributeForm' => $attributeForm->createView()
        ]);
    }

}