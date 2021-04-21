<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Event\ProductEvent;
use App\Form\AttributeType;
use App\Form\Type\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/products", name="admin_products_")
 * @IsGranted("ROLE_USER")
 */

class ProductController extends AbstractController
{
    private $em;

    private $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @Route("", methods={"GET", "POST"}, name="list")
     *
     */
    public function list(ProductRepository $repository) : Response
    {
        $products = $repository->findBy(['creator' => $this->getUser()]);

        return $this->render('admin/product/index.html.twig', [
            'products' => $products
        ]);

    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="new")
     */
    public function new(Request $request) : Response
    {
        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);

        if (!($form->isSubmitted() && $form->isValid())) {
            return $this->render('admin/product/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $event = new ProductEvent($form->getData());
        $this->dispatcher->dispatch($event, ProductEvent::NEW);

        if($product = $event->getProduct()) {
            $this->em->persist($product);
        }

        $this->addFlash(
            'success',
            sprintf('Product %s added', $product->getTitle())
        );

        $this->em->flush();

        return $this->redirectToRoute('admin_products_list');

    }

    /**
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="edit",  requirements={"id"="\d+"})
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

            return $this->redirectToRoute('admin_products_list');
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="show",  requirements={"id"="\d+"})
     * @Route("/{id}/attribute", methods={"POST"}, name="add_attribute",  requirements={"id"="\d+"})
     * @ParamConverter("product", class="App:Product")
     *
     */
    public function show(Product $product, Request $request) : Response
    {
        $form = $this->createForm(AttributeType::class, null, [
            'action' => $this->generateUrl('admin_products_add_attribute', ['id' => $product->getId()])
        ]);

        $form->handleRequest($request);

        if (!($form->isSubmitted() && $form->isValid())) {

            //TODO: get builder from service
            $formBuilder = $this->createFormBuilder();

            foreach ($product->getAttributes() as $attribute) {
                //TODO: need to set attribute by default
                $formBuilder->add($attribute->getName(), $attribute->getType(), [
                    'label' => $attribute->getLabel(),
                ]);
            }

            $formResult = $formBuilder->getForm();

            return $this->render('admin/product/show.html.twig', [
                'product' => $product,
                'form' => $form->createView(),
                'formResult' => $formResult->createView()
            ]);
        }

        $attribute = $form->getData();
        $attribute->setProduct($product);

        $this->getDoctrine()->getManager()->persist($attribute);

        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'success',
            sprintf('Attribute %s added', $attribute->getName())
        );

        return $this->redirectToRoute('admin_products_show', ['id' => $product->getId()]);

    }
}