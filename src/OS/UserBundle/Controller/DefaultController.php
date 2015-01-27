<?php

namespace OS\UserBundle\Controller;

use OS\ToolsBundle\Controller\BaseController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/user/toggle-receive-notification/", name="_user_toggle_receive_notification")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $user->setReceiveNotification(!$user->getReceiveNotification());

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();

        if ($user->getReceiveNotification()) {
            $this->flash('Now you will receive notification.');
        } else {
            $this->flash('Now you will not receive notification!', 'warning');
        }

        return $this->redirectToReferer();
    }
}
