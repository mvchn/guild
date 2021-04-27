<?php

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Order;
use App\Entity\OrderAttribute;
use App\Entity\Product;
use App\Entity\Stock;
use App\Event\OrderEvent;
use App\Event\ProductEvent;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ProductController extends AbstractController
{
    private $dispatcher;

    private $session;

    public function __construct(EventDispatcherInterface $dispatcher, SessionInterface $session)
    {
        $this->dispatcher = $dispatcher;
        $this->session = $session;
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
     * @ParamConverter("product", class="App:Product")
     */
    public function show(Product $product): Response
    {
        $stock = $this->getDoctrine()->getManager()->getRepository(Stock::class)->findBy(['product' => $product->getId()]);

        return $this->render('product/show_order.html.twig', [
            'product' => $product,
            'stock' => $stock,
        ]);
    }

    /**
     * @Route("/product/{id}/order/{stockId}", methods={"GET", "POST"}, name="product_order", requirements={"id"="\d+", "stockId"="\d+"})
     * @ParamConverter("product", class="App:Product")
     */
    public function order(Product $product, int $stockId, Request $request): Response
    {
        $stock = $this->getDoctrine()->getManager()->getRepository(Stock::class)->find($stockId);

        //TODO: get builder from service
        $formBuilder = $this->createFormBuilder();

        foreach ($product->getAttributes() as $attribute) {
            //TODO: need to set attribute by default
            $formBuilder->add($attribute->getName(), $attribute->getType(), [
                'label' => $attribute->getLabel(),
                'required' => $attribute->getRequired(),
                'help' => $attribute->getHelp(),
                'constraints' => [new NotBlank()],
            ]);
        }

        $formBuilder->add('save', SubmitType::class, ['label' => 'Confirm']);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        $event = new ProductEvent($product);
        $this->dispatcher->dispatch($event, ProductEvent::SHOW_ORDER);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = new Order();

            foreach ($form->getData() as $key => $item) {
                $orderAttribute = (new OrderAttribute())
                    ->setOrder($order)
                    ->setAttribute($this->getDoctrine()->getManager()->getRepository(Attribute::class)->findOneBy([
                        'product' => $product,
                        'name' => $key
                    ]))
                    ->setValue($item)
                ;
                $order->addOrderAttribute($orderAttribute);
            }

            $event = new OrderEvent($order);
            $this->dispatcher->dispatch($event, OrderEvent::CONFIRMED);
            $order->setStock($stock);  //TODO: maybe wrong place
            $stock->setOrder($order);  //TODO: maybe wrong place
            $order->addProduct($product); //TODO: wrong place

            $this->getDoctrine()->getManager()->persist($order);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Order created'
            );

            return $this->redirectToRoute('order_show_uuid', ['uuid' => (string)$order->getUuid()]);
        }

        return $this->render('product/order.html.twig', [
            'stock' => $stock,
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/stock/{id}/order", methods={"POST"}, name="stock_order", requirements={"id"="\d+"})
     * @ParamConverter("stock", class="App:Stock")
     */
    public function createOrder(Stock $stock) : Response
    {
        //TODO: maybe get from cookie
        if($this->session->get('order')) {
            $order = new Order($this->session->get('order'));
        } else {
            $order = new Order();
            $this->session->set('order', (string)$order->getUuid());
        }

        $stock->setOrder($order); //TODO: maybe choose one
        $order->setStock($stock);

        $this->getDoctrine()->getManager()->persist($order);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('order_edit', ['uuid' => (string)$order->getUuid()]);
    }

    /**
     * @Route("/orders/{uuid}/edit", methods={"GET", "POST"}, name="order_edit")
     * @ParamConverter("order", class="App:Order")
     */
    public function orderEdit(Order $order, Request $request): Response
    {
        //TODO: get builder from service
        $formBuilder = $this->createFormBuilder();

        foreach ($order->getStock()->getProduct()->getAttributes() as $attribute) { //TODO: check if attributes here
            //TODO: need to set attribute by default
            $formBuilder->add($attribute->getName(), $attribute->getType(), [
                'label' => $attribute->getLabel(),
                'required' => $attribute->getRequired(),
                'help' => $attribute->getHelp(),
                'constraints' => [new NotBlank()],
            ]);
        }

        $formBuilder->add('save', SubmitType::class, ['label' => 'Save']);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData() as $key => $item) {
                $attribute =  $this->getDoctrine()->getRepository(Attribute::class)->findOneBy([
                    'product' => $order->getStock()->getProduct(),
                    'name' => $key
                ]);

                $orderAttribute = $this->getDoctrine()->getRepository(OrderAttribute::class)->findOneBy([
                    'order' => $order,
                    'attribute' => $attribute
                ]);

                if(!$orderAttribute) {
                    $orderAttribute = (new OrderAttribute())
                        ->setOrder($order)
                        ->setAttribute($this->getDoctrine()->getManager()->getRepository(Attribute::class)->findOneBy([
                            'product' => $order->getProducts()->first(), //TODO: maybe two products
                            'name' => $key
                        ]))
                        ->setValue($item)
                    ;

                    $this->getDoctrine()->getManager()->persist($orderAttribute);
                    $order->addOrderAttribute($orderAttribute);
                }

                $orderAttribute->setValue($item);
                $orderAttribute->setAttribute($attribute);

            }

            $event = new OrderEvent($order);
            $this->dispatcher->dispatch($event, OrderEvent::CONFIRMED);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_show_uuid', ['uuid' => (string)$order->getUuid()]);
        }

        return $this->render('product/order.html.twig', [
            'form' => $form->createView(),
            'order' => $order
        ]);
    }
}
