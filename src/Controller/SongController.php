<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Song;
use App\Form\Type\SongType;
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
class SongController extends Controller
{
    /**
     * Show songs
     *
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $request, ObjectManager $om)
    {
        $songs = $om->getRepository(Song::class)->findByUser($this->getUser());

        return [
            'songs' => $songs,
        ];
    }

    /**
     * Add or edit a song
     *
     * @Route("/modify/{song}")
     * @Template
     */
    public function modifyAction(Request $request, ObjectManager $om, Song $song = null)
    {
        if (!$song) {
            if ($this->getUser()->getSongs()->count() >= 10) {
                throw new \RuntimeException('You cannot suggest more than 10 songs');
            }

            $song = new Song($this->getUser());
        }

        if ($song->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(SongType::class, $song, [
            'action' => $request->getRequestUri(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $om->persist($song);
            $om->flush();

            return $this->redirectToRoute('app_song_index');
        }

        return [
            'form' => $form->createView(),
            'song' => $song,
        ];
    }

    /**
     * Delete a song
     *
     * @Route("/delete/{song}")
     */
    public function deleteAction(ObjectManager $om, Song $song)
    {
        if ($song->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException();
        }

        $om->remove($song);
        $om->flush();

        return $this->redirectToRoute('app_song_index');
    }
}
