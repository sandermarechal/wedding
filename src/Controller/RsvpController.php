<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Guest;
use App\Form\Type\GuestType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Sander Marechal
 *
 * @Security("has_role('ROLE_VERIFIED')")
 */
class RsvpController extends Controller
{
    /**
     * Show guests
     *
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $request, ObjectManager $om)
    {
        $guests = $om->getRepository(Guest::class)->findByUser($this->getUser());

        return [
            'guests' => $guests,
        ];
    }

    /**
     * Add or edit a guest
     *
     * @Route("/modify/{guest}")
     * @Template
     */
    public function modifyAction(Request $request, ObjectManager $om, Guest $guest = null)
    {
        if (!$guest) {
            $guest = new Guest('');
            $guest->setUser($this->getUser());
        }

        if ($guest->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(GuestType::class, $guest, [
            'action' => $request->getRequestUri(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $om->persist($guest);
            $om->flush();

            return $this->redirectToRoute('app_rsvp_index');
        }

        return [
            'form' => $form->createView(),
            'guest' => $guest,
        ];
    }

    /**
     * Delete a guest
     *
     * @Route("/delete/{guest}")
     */
    public function deleteAction(ObjectManager $om, Guest $guest)
    {
        if ($guest->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        $om->remove($guest);
        $om->flush();

        return $this->redirectToRoute('app_rsvp_index');
    }
}
