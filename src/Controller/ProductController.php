<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\OrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", methods={"GET"}, name="product_list")
     */
    public function list() : Response
    {
        $products = $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/{id}", methods={"GET"}, name="product_show", requirements={"id"="\d+"})
     * @ParamConverter("product", class="App:Product")
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show_order.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product/{id}/order", methods={"GET", "POST"}, name="product_order", requirements={"id"="\d+"})
     * @ParamConverter("product", class="App:Product")
     */
    public function order(Product $product, Request $request): Response
    {
        $form = $this->createForm(OrderType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $order = $form->getData();
            $order->addProduct($product);

            $this->addFlash(
                'success',
                'Order created'
            );

            $this->getDoctrine()->getManager()->persist($order);
            $this->getDoctrine()->getManager()->flush();

            //TODO: redirect to thank you page
            return $this->redirectToRoute('order_show', ['id' => $order->getId()]);
        }

        return $this->render('product/order.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
