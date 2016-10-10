<?php

namespace Muspelheim\EmbeddablesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MuspelheimEmbeddablesBundle:Default:index.html.twig');
    }
}
