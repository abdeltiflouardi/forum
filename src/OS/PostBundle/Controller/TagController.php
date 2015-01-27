<?php

namespace OS\PostBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OS\ToolsBundle\Controller\BaseController as Controller;

class TagController extends Controller
{
    /**
     * @Route("/tags/", name="_tag")
     * @Template()
     */
    public function indexAction()
    {
        $query = $this->getTagEntityManager()->getQuery(array(), array('t.count' => 'DESC'));

        $this->set('page', $this->pager($query, array('itemsPerPage' => 50)));

        return $this->getViewData();
    }

    /**
     * @return \OS\PostBundle\EntityManager\TagManager
     */
    private function getTagEntityManager()
    {
        return $this->container->get('os_tag.entity.manager');
    }
}
