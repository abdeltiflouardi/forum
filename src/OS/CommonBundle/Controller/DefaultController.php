<?php

namespace OS\CommonBundle\Controller;

use OS\ToolsBundle\Controller\BaseController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_home")
     */
    public function indexAction()
    {
        return $this->redirectTo('_post');
    }
}
