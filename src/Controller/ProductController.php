<?php

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Order;
use App\Entity\OrderAttribute;
use App\Entity\Product;
use App\Entity\Stock;
use App\Event\OrderEvent;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
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

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'stock' => $stock,
        ]);
    }

    /**
     * @Route("/stock/{id}/order", methods={"GET", "POST"}, name="stock_order", requirements={"id"="\d+"})
     * @ParamConverter("stock", class="App:Stock")
     */
    public function createOrder(Stock $stock, Request $request) : Response
    {
        //TODO: maybe get from cookie
        //TODO: check order status
        if ($this->session->get('order')) {
            $order = new Order($this->session->get('order'));
        } else {
            $order = new Order();
            $this->session->set('order', (string)$order->getUuid());
        }

        $form = $this->createOrderForm($stock->getProduct());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($form->getData() as $key => $item) {
                $attribute =  $this->getDoctrine()->getRepository(Attribute::class)->findOneBy([
                    'product' => $stock->getProduct(),
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

            $stock->addOrder($order); //TODO: maybe choose one

            $this->getDoctrine()->getManager()->persist($order);

            $event = new OrderEvent($order);
            $this->dispatcher->dispatch($event, OrderEvent::NEW);

            $event = new OrderEvent($order);
            $this->dispatcher->dispatch($event, OrderEvent::CONFIRMED);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_show_uuid', ['uuid' => (string)$order->getUuid()]);

        }

        return $this->render('product/order.html.twig', [
            'form' => $form->createView(),
            'stock' => $stock
        ]);

    }

    public function createOrderForm($product) : FormInterface
    {
        $formBuilder = $this->createFormBuilder();

        foreach ($product->getAttributes() as $attribute) { //TODO: check if attributes here
            //TODO: need to set attribute by default
            $formBuilder->add($attribute->getName(), $attribute->getType(), [
                'label' => $attribute->getLabel(),
                'required' => $attribute->getRequired(),
                'help' => $attribute->getHelp(),
                'constraints' => [new NotBlank()],
            ]);
        }

        $formBuilder->add('save', SubmitType::class, ['label' => 'Save']);

        return $formBuilder->getForm();
    }

}
