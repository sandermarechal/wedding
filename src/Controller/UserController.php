<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Guest;
use App\Entity\User;
use App\Form\Type\CodeType;
use App\Form\Type\RegistrationType;
use App\Form\Type\ResetPasswordType;
use App\Form\Type\ResetPasswordVerifyType;
use App\Model\Code;
use Doctrine\Common\Persistence\ObjectManager;
use Prezent\InkBundle\Mail\TwigFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
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

    /**
     * @Route("/verify")
     * @Security("has_role('ROLE_USER')")
     * @Template
     */
    public function verifyCodeAction(Request $request, ObjectManager $om, TokenStorageInterface $tokenStorage, SessionInterface $session)
    {
        $code = new Code();

        $form = $this->createForm(CodeType::class, $code);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($code->isValid()) {
                $user = $this->getUser();
                $user->addRole('ROLE_VERIFIED');

                if (!$om->getRepository(Guest::class)->findOneByName($user->getName())) {
                    $guest = new Guest($user->getName());
                    $guest->setEmail($user->getEmail());
                    $guest->setUser($user);

                    $om->persist($guest);
                }

                $om->flush();

                // Refresh token or the new role will not work
                $tokenStorage->setToken(new UsernamePasswordToken($user, null, 'main', $user->getRoles()));

                if ($url = $session->get('verify_url')) {
                    return $this->redirect($url);
                }

                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('error', 'flash.code.error');
            }
        }
        
        return [
            'form' => $form->createView(),
        ];
    }
}
