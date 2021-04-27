<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/orders", name="admin_orders_")
 * @IsGranted("ROLE_USER")
 */

class OrderController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("", methods={"GET"}, name="list")
     *
     */
    public function list(OrderRepository $repository) : Response
    {
        $orders = $repository->findAll();

        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders
        ]);

    }

    /**
     * @Route("/{id}", methods={"GET"}, name="show",  requirements={"id"="\d+"})
     * @ParamConverter("order", class="App:Order")
     *
     */
    public function show(Order $order) : Response
    {
        return $this->render('admin/order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="edit",  requirements={"id"="\d+"})
     * @ParamConverter("product", class="App:Order")
     * //TODO: use edit order only with special permissions
     *
     */
    public function edit(Order $order, Request $request) : Response
    {
        $form = $this->createForm(OrderType::class, $order);

        $status = $order->getStatus();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash(
                'info',
                'Order changed'
            );

            if($status !== $order->getStatus() && 'completed' === $order->getStatus()) {
                $this->addFlash(
                    'success',
                    'Order completed'
                );

                //TODO: get email from order attributes

                $this->mailer->send((new Email())
                    ->subject(sprintf('Order %s completed', (string)$order->getId())) //TODO: generate non-autoincrement id
                    ->from('admin@example.com')
                    //->to($order->getEmail())
                    ->text(sprintf('Your result link: %s', $order->getProducts()->first()->getDestinationUrl()))
                );
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_orders_list');
        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }
}