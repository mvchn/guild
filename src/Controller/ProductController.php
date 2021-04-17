<?php

namespace App\Controller;

use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product_show", requirements={"id"="\d+"})
     * @ParamConverter("product", class="App:Product")
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show_order.html.twig', [
            'product' => $product,
        ]);
    }
}
