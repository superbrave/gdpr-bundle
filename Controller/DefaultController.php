<?php

namespace SuperBrave\GdprBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SuperBraveGdprBundle:Default:index.html.twig');
    }
}
