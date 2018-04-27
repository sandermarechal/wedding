<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegistrationType;
use App\Form\Type\ResetPasswordType;
use App\Form\Type\ResetPasswordVerifyType;
use Doctrine\Common\Persistence\ObjectManager;
use Prezent\InkBundle\Mail\TwigFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * User controller
 */
class UserController extends Controller
{
    /**
     * Login or register an account
     *
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $request, AuthenticationUtils $authUtils)
    {
        $user = new User($request->getLocale());

        $form = $this->createForm(RegistrationType::class, $user, [
            'action' => $this->generateUrl('app_user_register'),
        ]);

        return [
            'form' => $form->createView(),
            'lastUser' => $authUtils->getLastUsername(),
            'error' => $authUtils->getLastAuthenticationError(),
        ];
    }

    /**
     * Login
     *
     * @Route("/login")
     * @Template
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        return [
            'lastUser' => $authUtils->getLastUsername(),
            'error' => $authUtils->getLastAuthenticationError(),
        ];
    }

    /**
     * Register an account
     *
     * @Route("/register")
     * @Template
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User($request->getLocale());

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'flash.register.success');

            return $this->redirectToRoute('app_user_index');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/reset-password")
     * @Template
     */
    public function resetPasswordAction(
        Request $request,
        ObjectManager $om,
        TwigFactory $factory,
        \Swift_Mailer $mailer
    ) {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user = $om->getRepository(User::class)->findOneByEmail($form->get('email')->getData())) {
                if (!$user->getToken()) {
                    $user->setToken(bin2hex(random_bytes(16)));
                    $om->flush($user);
                }

                $message = $factory->getMessage('mail/reset_password.eml.twig', [
                    'user' => $user,
                ]);

                $message->setFrom(getenv('MAILER_FROM'));
                $message->setTo($user->getEmail());

                $mailer->send($message);
            }

            $this->addFlash('success', 'flash.reset_password.sent');
            return $this->redirectToRoute('app_user_login');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/reset-password/verify/{token}")
     * @Template
     */
    public function resetPasswordVerifyAction(
        UserPasswordEncoderInterface $encoder,
        ObjectManager $om,
        Request $request,
        string $token
    ) {
        if (!$user = $om->getRepository(User::class)->findOneByToken($token)) {
            $this->addFlash('error', 'flash.reset_password_verify.invalid');
            return $this->redirectToRoute('app_user_login');
        }

        $form = $this->createForm(ResetPasswordVerifyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setToken(null);
            $user->setPassword($encoder->encodePassword($user, $form->get('password')->getData()));

            $om->flush($user);

            $this->addFlash('success', 'flash.reset_password_verify.success');
            return $this->redirectToRoute('app_user_login');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
