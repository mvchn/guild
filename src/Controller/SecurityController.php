<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $em;

    private $session;

    private $login;

    private $guard;

    private $mailer;

    private $defaultEmail;

    public function __construct(EntityManagerInterface $em, SessionInterface $session, LoginFormAuthenticator $login, GuardAuthenticatorHandler $guard, MailerInterface $mailer, string $defaultEmail)
    {
        $this->em = $em;
        $this->session = $session;
        $this->login = $login;
        $this->guard = $guard;
        $this->mailer = $mailer;
        $this->defaultEmail = $defaultEmail;
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('app_homepage', ['target' => 'registered']);
         }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/forgot-password", methods="GET|POST", name="user_forgot_password")
     */
    public function forgotPassword(Request $request): Response
    {
        $contact = new \stdClass();
        $contact->email = null;
        $form = $this->createFormBuilder($contact)
            ->add('email', EmailType::class, [
                'label' => 'label.email',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $contact->email]);

            if(!$user) {
                $form->addError(new FormError('User not found'));
                return $this->render('security/forgot_password.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $user->setConfirmation(rand(111111, 999999));
            $this->session->set('restoreUser', $user);

            return $this->redirectToRoute('user_confirmation_code');

        }

        return $this->render('security/forgot_password.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/confirmation-code", methods="GET|POST", name="user_confirmation_code")
     */
    public function confirmationCode(Request $request): Response
    {
        $user = $this->session->get('restoreUser');

        if(!$user instanceof User || !$user->getConfirmation()) {
            //TODO: send flash message
            return $this->redirectToRoute('user_forgot_password');
        }

        //TODO: service here
        $this->mailer->send((new NotificationEmail())
            ->subject('New password submitted')
            ->htmlTemplate('emails/verification.html.twig')
            ->from($this->defaultEmail)
            ->to($user->getEmail())
            ->context(['user' => $user, 'confirmation' => $user->getConfirmation()])
        );

        $confirmation = new \stdClass();
        $confirmation->code = null;
        $form = $this->createFormBuilder($confirmation)
            ->add('code', TextType::class, [
                'label' => 'label.code',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user->getConfirmation() !== $confirmation->code) {
                $form->addError(new FormError('Wrong confirmation code'));
                return $this->render('security/forgot_password.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            return $this->guard->authenticateUserAndHandleSuccess($user, $request, $this->login,'main');
        }

        return $this->render('security/forgot_password.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
