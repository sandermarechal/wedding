<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Grid\Type\UserGridType;
use Prezent\CrudBundle\Controller\CrudController;
use Prezent\CrudBundle\Model\Configuration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * User management
 *
 * @author Sander Marechal
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class UserAdminController extends CrudController
{
    protected function configure(Request $request, Configuration $config)
    {
        $config
            ->setEntityClass(User::class)
            ->setFormType(UserType::class)
            ->setGridType(UserGridType::class)
        ;
    }
}
