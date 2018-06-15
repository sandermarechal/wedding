<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Song;
use App\Form\Type\SongType;
use App\Grid\Type\SongGridType;
use Prezent\CrudBundle\Controller\CrudController;
use Prezent\CrudBundle\CrudEvents;
use Prezent\CrudBundle\Model\Configuration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Sander Marechal
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class SongAdminController extends CrudController
{
    /**
     * {@inheritDoc}
     */
    protected function configure(Request $request, Configuration $config)
    {
        $config
            ->setEntityClass(Song::class)
            ->setFormType(SongType::class)
            ->setGridType(SongGridType::class)
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function newInstance(Request $request)
    {
        return new Song($this->getUser());
    }

    /**
     * Set song status
     *
     * @Route("/{action}/{id}", requirements={"action"="approve|reject"})
     * @param $id
     * @return RedirectResponse
     */
    public function statusAction(Request $request, $action, $id)
    {
        $configuration = $this->getConfiguration($request);
        $object = $this->findObject($request, $id);

        if ($action === 'approve') {
            $object->approve();
        } else {
            $object->reject();
        }

        $om = $this->getObjectManager();

        try {
            $om->flush();
            $this->addFlash('success', sprintf('flash.%s.%s.success', $configuration->getName(), $action));
        } catch (\Exception $e) {
            $event->setException($e);
            $this->addFlash('error', sprintf('flash.%s.%s.error', $configuration->getName(), $action));
        }

        return $this->redirectToRoute(
            $configuration->getRoutePrefix() . 'index',
            $configuration->getRouteParameters()
        );
    }
}
