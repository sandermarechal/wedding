<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Guest;
use App\Form\Type\GuestType;
use App\Grid\Type\GuestGridType;
use Prezent\CrudBundle\Controller\CrudController;
use Prezent\CrudBundle\Model\Configuration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Guest management
 *
 * @author Sander Marechal
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class GuestAdminController extends CrudController
{
    protected function configure(Request $request, Configuration $config)
    {
        $config
            ->setEntityClass(Guest::class)
            ->setFormType(GuestType::class)
            ->setFormOptions(['admin' => true])
            ->setGridType(GuestGridType::class)
        ;
    }
}
