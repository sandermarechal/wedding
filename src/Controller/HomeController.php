<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Home controller
 *
 * @Route("/")
 */
class HomeController extends Controller
{
    /**
     * Site index
     *
     * @Route("/")
     * @Template
     */
    public function indexAction(): array
    {
        return [];
    }
}
