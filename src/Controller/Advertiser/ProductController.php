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
        $products = $this->em->getRepository(Product::class)->findBy([]);

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash(
                'success',
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
     * @Route("/products/new", methods={"GET", "POST"}, name="products_new")
     */
    public function new(Request $request) : Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();

            $this->addFlash(
                'success',
                'Product added'
            );

            $this->em->persist($product);
            $this->em->flush();

            //TODO: redirect to new if enabled
            return $this->redirectToRoute('products_list');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/products/{id}/edit", methods={"GET", "POST"}, name="products_edit",  requirements={"id"="\d+"})
     * @ParamConverter("product", class="App:Product")
     *
     */
    public function edit(Product $product, Request $request) : Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash(
                'success',
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

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            //'attributeForm' => $attributeForm->createView()
        ]);
    }

}