<?php

namespace App\Controller\Advertiser;

use App\Entity\Product;
use App\Form\Type\ProductType;
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
     * @Route("/products", methods={"GET", "POST"}, name="products_list")
     *
     */
    public function list(Request $request) : Response
    {
        $user = $this->getUser();

        $products = $this->em->getRepository(Product::class)->findBy([]);

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash(
                'primary',
                'Product added'
            );

            $this->em->flush();

            return $this->redirectToRoute('products_list');
        }

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
            'products' => $products
        ]);
    }

    /**
     * @IsGranted("ROLE_PRODUCT_OWNER")
     * @Route("/products/{id}/edit", methods={"GET", "POST"}, name="products_edit",  requirements={"id"="\d+"})
     * @ParamConverter("product", class="App:Product")
     *
     */
    public function edit(Product $product, Request $request) : Response
    {
        $editForm = $this->createForm(ProductType::class, $product);

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

//        $attribute = new Attribute();
//        $attributeForm = $this->createForm(AttributeFormType::class, $attribute);
//
//        $attributeForm->handleRequest($request);
//        if ($attributeForm->isSubmitted() && $attributeForm->isValid()) {
//
//            $attribute->setProduct($product);
//            $this->em->persist($attribute);
//
//            $this->addFlash(
//                'primary',
//                'Product changed'
//            );
//
//            $this->em->flush();
//
//            return $this->redirectToRoute('products_edit', ['id' => $product->getId()]);
//        }
//
//        $goal = new Goal();
//        $goalForm = $this->createForm(GoalFormType::class, $goal);
//
//        $goalForm->handleRequest($request);
//        if ($goalForm->isSubmitted() && $attributeForm->isValid()) {
//
//            $goal->setProduct($product);
//            $this->em->persist($attribute);
//
//            $this->addFlash(
//                'primary',
//                'Product changed'
//            );
//
//            $this->em->flush();
//
//            return $this->redirectToRoute('products_edit', ['id' => $product->getId()]);
//        }

        return $this->render('advertiser/product/edit.html.twig', [
            'product' => $product,
            'editForm' => $editForm->createView(),
            //'attributeForm' => $attributeForm->createView()
        ]);
    }

}