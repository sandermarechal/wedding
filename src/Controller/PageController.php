<?php

declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Sander Marechal
 */
class PageController extends Controller
{
    /**
     * @Route("/{page}")
     */
    public function indexAction(string $page)
    {
        return $this->render('page/' . $page . '.html.twig');
    }
}
