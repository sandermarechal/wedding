<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegistrationType;
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
}
