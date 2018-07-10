<?php

declare(strict_types=1);

namespace App\Grid\Type;

use App\Grid\Extension\RsvpType;
use Prezent\Grid\BaseGridType;
use Prezent\Grid\Extension\Core\Type;
use Prezent\Grid\GridBuilder;

/**
 * Guest grid type
 *
 * @author Sander Marechal
 */
class GuestGridType extends BaseGridType
{
    public function buildGrid(GridBuilder $builder, array $options = [])
    {
        $builder
            ->addColumn('name', Type\StringType::class, [
                'label' => 'grid.guest.column.name',
                'sortable' => true,
                'attr' => [
                    'class' => function ($guest) {
                        return $guest->isVerified() ? 'verified' : 'new';
                    },
                ]
            ])
            ->addColumn('email', Type\StringType::class, [
                'label' => 'grid.guest.column.email',
                'sortable' => true,
            ])
            ->addColumn('ceremony', RsvpType::class, [
                'label' => 'grid.guest.column.ceremony',
            ])
            ->addColumn('party', RsvpType::class, [
                'label' => 'grid.guest.column.party',
            ])
            ->addColumn('user', Type\StringType::class, [
                'label' => 'grid.guest.column.user',
                'property_path' => function ($guest) {
                    if ($user = $guest->getUser()) {
                        return $user->getEmail();
                    }

                    return '';
                },
            ])
            ->addAction('edit', [
                'icon' => 'edit',
                'label' => 'grid.guest.action.edit',
                'route' => 'app_guestadmin_edit',
                'route_parameters' => ['id' => '{id}'],
            ])
            ->addAction('delete', [
                'icon' => 'trash',
                'label' => 'grid.guest.action.delete',
                'route' => 'app_guestadmin_delete',
                'route_parameters' => ['id' => '{id}'],
                'attr' => ['class' => 'delete']
            ])
        ;
    }
}
