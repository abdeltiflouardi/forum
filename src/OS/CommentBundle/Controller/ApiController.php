<?php

namespace OS\CommentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OS\ToolsBundle\Controller\BaseController as Controller;
use OS\CommentBundle\EntityManager\CommentManager as CommentEntityManager;
use OS\CommonBundle\Entity\Post;

class ApiController extends Controller
{
    /**
     * @Route("/comments/count/{post_id}/")
     * @ParamConverter("post", class="OSCommonBundle:Post", options={"id": "post_id"})
     */
    public function countAction(Post $post)
    {
        return $this->renderText($this->getCommentEntityManager()->getCountPostComments($post));
    }

    /**
     * @Route("/post/{id}/last-comment/")
     * @ParamConverter("post", class="OSCommonBundle:Post")
     * @Template("OSCommentBundle:Partials:_comment_item.html.twig")
     */
    public function lastAction(Post $post)
    {
        $comment = $this->getCommentEntityManager()->getLastByPost($post);
        $this->set('isLast', 1);
        $this->set('comment', $comment);

        return $this->getViewData();
    }

    /**
     * @Route("/post/{id}/comments/", name="_api_post_comments")
     * @ParamConverter("post", class="OSCommonBundle:Post")
     * @Template("OSCommentBundle:Partials:_comments.html.twig")
     */
    public function listAction(Post $post)
    {
        $comments = $this->getCommentEntityManager()->getAllByPost($post);
        $this->set('comments', $comments);

        return $this->getViewData();
    }

    /**
     * @return CommentEntityManager
     */
    public function getCommentEntityManager()
    {
        return $this->container->get('os_comment.entity.manager');
    }
}
