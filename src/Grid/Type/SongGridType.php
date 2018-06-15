<?php

declare(strict_types=1);

namespace App\Grid\Type;

use App\Entity\Song;
use Prezent\Grid\BaseGridType;
use Prezent\Grid\Extension\Core\Type;
use Prezent\Grid\GridBuilder;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Sander Marechal
 */
class SongGridType extends BaseGridType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function buildGrid(GridBuilder $builder, array $options = [])
    {
        $builder
            ->addColumn('performer', Type\StringType::class, [
                'label' => 'grid.song.column.performer',
                'sortable' => true,
            ])
            ->addColumn('title', Type\StringType::class, [
                'label' => 'grid.song.column.title',
                'sortable' => true,
            ])
            ->addColumn('user', Type\StringType::class, [
                'label' => 'grid.song.column.user',
                'property_path' => function ($song) {
                    if ($user = $song->getUser()) {
                        return $user->getName();
                    }

                    return '';
                }
            ])
            ->addColumn('status', Type\StringType::class, [
                'label' => 'grid.song.column.status',
                'sortable' => true,
                'property_path' => function ($song) {
                    return $this->translator->trans('view.song.status.' . $song->getStatus());
                }
            ])
            ->addAction('approve', [
                'icon' => 'thumbs-up',
                'label' => 'grid.song.action.approve',
                'route' => 'app_songadmin_status',
                'route_parameters' => ['action' => 'approve', 'id' => '{id}'],
                'visible' => function ($song) {
                    return $song->getStatus() !== Song::STATUS_APPROVED;
                }
            ])
            ->addAction('reject', [
                'icon' => 'thumbs-down',
                'label' => 'grid.song.action.reject',
                'route' => 'app_songadmin_status',
                'route_parameters' => ['action' => 'reject', 'id' => '{id}'],
                'visible' => function ($song) {
                    return $song->getStatus() !== Song::STATUS_REJECTED;
                }
            ])
            ->addAction('edit', [
                'icon' => 'edit',
                'label' => 'grid.song.action.edit',
                'route' => 'app_songadmin_edit',
                'route_parameters' => ['id' => '{id}'],
            ])
            ->addAction('delete', [
                'icon' => 'trash',
                'label' => 'grid.song.action.delete',
                'route' => 'app_songadmin_delete',
                'route_parameters' => ['id' => '{id}'],
                'attr' => ['class' => 'delete']
            ])
        ;
    }
}
