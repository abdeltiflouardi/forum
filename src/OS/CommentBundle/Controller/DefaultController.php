<?php

namespace OS\CommentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use OS\ToolsBundle\Controller\BaseController as Controller;
use OS\CommentBundle\FormManager\CommentManager as CommentFormManager;
use OS\CommonBundle\Entity\Post;

class DefaultController extends Controller
{

    /**
     * @Route("/post/{id}/new-comment/", name="_post_newcomment")
     * @ParamConverter("post", class="OSCommonBundle:Post")
     * @Template()
     */
    public function newAction(Post $post)
    {
        $action = $this->generateUrl('_post_newcomment', array('id' => $post->getId()));
        $form = $this->getCommentFormManager()->createForm(array('action' => $action));

        $formHandler = $this->getCommentFormManager()->getHandler();
        if ($formHandler->process($form, array('post' => $post))) {
            $this->flash('Commentaire enregistrée.');

            return $this->redirectToReferer();
        }

        $this->set('form', $form->createView());

        return $this->getViewData();
    }

    /**
     * 
     * @return CommentFormManager
     */
    private function getCommentFormManager()
    {
        return $this->container->get('os_comment.form.manager');
    }
}
