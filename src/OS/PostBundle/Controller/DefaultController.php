<?php

namespace OS\PostBundle\Controller;

use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OS\ToolsBundle\Controller\BaseController as Controller;
use OS\PostBundle\EntityManager\PostManager as PostEntityManager;
use OS\PostBundle\FormManager\PostManager as PostFormManager;
use OS\CommonBundle\Entity\Post;

class DefaultController extends Controller
{
    /**
     * @Route("/posts/", name="_post")
     * @Template()
     */
    public function indexAction()
    {
        $source = $this->getRequest()->query->get('s');
        switch ($source) {
            case 'p':
                $where = array('p.enabled = 1', '(p.ratingUp - p.ratingDown) > 0');
                $order = array('p.ratingUp - p.ratingDown' => 'DESC');

                $this->set('selected', 'popular');
                break;
            case 'mq':
                $where = array('p.enabled = 1', sprintf('p.user = %d', $this->getUser()->getId()));
                $order = array('p.createdAt' => 'DESC');

                $this->set('selected', 'myquestions');
                break;
            case 'ma':
                $where = array('p.enabled = 1', 'USERID_ANSWERS' => $this->getUser()->getId());
                $order = array('p.createdAt' => 'DESC');

                $this->set('selected', 'myanswers');
                break;
            case 'mn':
                $where = array('p.enabled = 1', 'USERID_NETWORK' => $this->getUser()->getId());
                $order = array('p.createdAt' => 'DESC');

                $this->set('selected', 'mynetwork');
                break;
            case 'mb':
                $where = array('p.enabled = 1', 'USERID_BOOKMARK' => $this->getUser()->getId());
                $order = array('p.createdAt' => 'DESC');

                $this->set('selected', 'mybookmark');
                $this->set('menu', 2);
                break;
            default:
                if ($q = $this->getRequest()->query->get('q')) {

                    $where = array('p.enabled = 1', 'p.title LIKE :title' => '%' . $q . '%');
                } elseif ($tagname = $this->getRequest()->query->get('t')) {
                    $where = array('TAGNAME' => $tagname, 'p.enabled = 1');
                } else {
                    $where = $this->isGranted('ROLE_MODERATOR') ? array() : array('p.enabled = 1');
                }
                $order = array('p.createdAt' => 'DESC');

                $this->set('selected', 'all');
                break;
        }
        $query = $this->getPostEntityManager()->getQuestionsQuery($where, $order);

        $this->set('page', $this->pager($query));

        return $this->getViewData();
    }

    /**
     * @Route("/post/{id}/", name="_post_show", requirements={"id" = "\d+"})
     * @ParamConverter("post", class="OSCommonBundle:Post")
     * @Template()
     */
    public function showAction(Post $post)
    {
        // Increment view count of current post
        $session = (array)$this->getRequest()->getSession()->get('viewed');
        if (!in_array($post->getId(), $session)) {
            $this->getPostEntityManager()->save($post->incViewCount());

            array_push($session, $post->getId());

            $this->getRequest()->getSession()->set('viewed', $session);
        }

        $form = $this->getPostFormManager()->createAnswerForm();

        $formOptions = array('Parent' => $post, 'defaultPostStatus' => $this->getParameter('default_post_status'));

        $formHandler = $this->getPostFormManager()->getHandler();
        if ($formHandler->process($form, $formOptions)) {
            $this->flash('Réponse enregistrée.');

            return $this->redirectToReferer();
        }

        $this->set('post', $post);
        $this->set('form_answer', $form->createView());

        return $this->getViewData();
    }

    /**
     * @Route("/post/new/", name="_post_new")
     * @Template()
     */
    public function newAction()
    {
        $form = $this->getPostFormManager()->createForm();

        $formHandler = $this->getPostFormManager()->getHandler();
        if ($formHandler->process($form, array('defaultPostStatus' => $this->getParameter('default_post_status')))) {
            $this->flash('Question enregistrée.');

            return $this->redirectTo('_post');
        }

        $this->set('form', $form->createView());

        return $this->getViewData();
    }

    /**
     * @Route("/api/post/{id}/answers/", name="_api_post_answers", requirements={"id" = "\d+"})
     * @ParamConverter("post", class="OSCommonBundle:Post")
     * @Template("OSPostBundle:Partials:_answers.html.twig")
     */
    public function answersAction(Post $post)
    {
        $pager = $this->getPostEntityManager()->getPostAnswersQuery(
            $post,
            $this->isGranted('ROLE_MODERATOR') ? array() : array('p.enabled = 1'),
            array('p.createdAt' => 'DESC')
        );
        $page = $this->pager($pager);
        $this->set('page', $page);

        return $this->getViewData();
    }

    /**
     * @Route("/post/last/", name="_post_last")
     * @Template("OSPostBundle:Partials:_last.html.twig")
     */
    public function lastAction()
    {
        $this->set('post', $this->getPostEntityManager()->getLastPost());

        return $this->getViewData();
    }

    /**
     * @Route("/post/{id}/rating/{flag}/", name="_api_post_rating", requirements={"id": "\d+", "flag": "\d+"})
     * @ParamConverter("post", class="OSCommonBundle:Post")
     */
    public function ratingAction(Post $post, $flag)
    {
        $return = array('class' => 'text-green', 'message' => 'Done.');
        try {
            $this->getPostEntityManager()->saveRating($post, $this->getUser(), $flag);

            $return['count'] = $this->forward(
                'OSPostBundle:Api:countRating',
                array('flag' => 'diff', 'id' => $post->getId())
            )->getContent();
        } catch (DBALException $dbal) {
            unset($dbal);

            $return = array('class' => 'text-red', 'message' => 'Duplicated.');
        }

        if ($this->getRequest()->isXmlHttpRequest()) {
            return $this->renderJson($return);
        } else {
            return $this->redirectToReferer();
        }
    }

    /**
     * @Route("/post/{id}/toggle-status/", name="_post_togglestatus", requirements={"id": "\d+"})
     * @ParamConverter("post", class="OSCommonBundle:Post")
     */
    public function toggleStatusAction(Post $post)
    {
        $post->setEnabled(!$post->getEnabled());

        $this->getPostEntityManager()->save($post);

        return $this->redirectToReferer();
    }

    /**
     * @Route("/post/{id}/bookmark/add/", name="_post_bookmark_add", requirements={"id": "\d+"})
     * @ParamConverter("post", class="OSCommonBundle:Post")
     */
    public function addBookmarkAction(Post $post)
    {
        $this->getUser()->addBookmark($post);
        $post->addBookmarkedBy($this->getUser());

        $this->getPostEntityManager()->save($this->getUser(), false);
        $this->getPostEntityManager()->save($post);

        return $this->redirectToReferer();
    }

    /**
     * @Route("/post/{id}/bookmark/remove/", name="_post_bookmark_remove", requirements={"id": "\d+"})
     * @ParamConverter("post", class="OSCommonBundle:Post")
     */
    public function removeBookmarkAction(Post $post)
    {
        $post->removeBookmarkedBy($this->getUser());
        $this->getUser()->removeBookmark($post);

        $this->getPostEntityManager()->save($this->getUser(), false);
        $this->getPostEntityManager()->save($post);

        return $this->redirectToReferer();
    }

    /**
     * 
     * @return PostFormManager
     */
    private function getPostFormManager()
    {
        return $this->container->get('os_post.form.manager');
    }

    /**
     * @return PostEntityManager
     */
    private function getPostEntityManager()
    {
        return $this->container->get('os_post.entity.manager');
    }
}
