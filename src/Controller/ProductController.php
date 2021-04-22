<?php

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Order;
use App\Entity\OrderAttribute;
use App\Entity\Product;
use App\Event\ProductEvent;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ProductController extends AbstractController
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @Route("/products", methods={"GET"}, name="product_list")
     */
    public function list(ProductRepository $repository) : Response
    {
        $products = $repository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/{id}", methods={"GET"}, name="product_show", requirements={"id"="\d+"})
     * @Route("/product/{id}/attribute", methods={"POST"}, name="attribute_create", requirements={"id"="\d+"})
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
        //TODO: get builder from service
        $formBuilder = $this->createFormBuilder();

        foreach ($product->getAttributes() as $attribute) {
            //TODO: need to set attribute by default
            $formBuilder->add($attribute->getName(), $attribute->getType(), [
                'label' => $attribute->getLabel(),
                'required' => $attribute->getRequired(),
                'constraints' => [new NotBlank()],
            ]);
        }

        $formBuilder->add('save', SubmitType::class, ['label' => 'Save']);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        $event = new ProductEvent($product);
        $this->dispatcher->dispatch($event, ProductEvent::SHOW_ORDER);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = (new Order())
                ->setName('test')
                ->setEmail('email@email.com')
            ;

            foreach ($form->getData() as $key => $item) {
                $orderAttribute = (new OrderAttribute())
                    ->setOrdr($order)
                    ->setAttribute($this->getDoctrine()->getManager()->getRepository(Attribute::class)->findOneBy([
                        'product' => $product,
                        'name' => $key
                    ]))
                    ->setValue($item)
                ;
                $order->addOrderAttribute($orderAttribute);
            }

            $this->dispatcher->dispatch($event, ProductEvent::CREATE_ORDER);

            $order->addProduct($product);

            $this->getDoctrine()->getManager()->persist($order);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Order created'
            );

            //TODO: redirect to thank you page
            return $this->redirectToRoute('order_show', ['id' => $order->getId()]);
        }

        return $this->render('product/order.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
