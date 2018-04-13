<?php

declare(strict_types=1);

namespace App\Grid\Type;

use Prezent\Grid\BaseGridType;
use Prezent\Grid\Extension\Core\Type;
use Prezent\Grid\GridBuilder;

/**
 * User grid type
 *
 * @author Sander Marechal
 */
class UserGridType extends BaseGridType
{
    public function buildGrid(GridBuilder $builder, array $options = [])
    {
        $builder
            ->addColumn('id', Type\StringType::class, [
                'label' => 'grid.user.column.id',
            ])
            ->addColumn('name', Type\StringType::class, [
                'label' => 'grid.user.column.name',
            ])
            ->addColumn('email', Type\StringType::class, [
                'label' => 'grid.user.column.email',
            ])
            ->addColumn('locale', Type\StringType::class, [
                'label' => 'grid.user.column.locale',
            ])
            ->addAction('edit', [
                'icon' => 'edit',
                'label' => 'grid.user.action.edit',
                'route' => 'app_useradmin_edit',
                'route_parameters' => ['id' => '{id}'],
            ])
            ->addAction('delete', [
                'icon' => 'trash',
                'label' => 'grid.user.action.delete',
                'route' => 'app_useradmin_delete',
                'route_parameters' => ['id' => '{id}'],
                'attr' => ['class' => 'delete']
            ])
        ;
    }
}
