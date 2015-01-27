<?php

namespace OS\PostBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use OS\ToolsBundle\Controller\BaseController as Controller;
use OS\PostBundle\EntityManager\PostManager as PostEntityManager;
use OS\PostBundle\EntityManager\TagManager as TagEntityManager;
use OS\CommonBundle\Entity\Post;
use OS\CommonBundle\Entity\Tag;
use OS\CommonBundle\Entity\Rating;
use OS\CommonBundle\Entity\User;
use OS\ToolsBundle\Util\String;

class ApiController extends Controller
{

    /**
     * @Route("/api/add-tag/", name="_api_add_tag")
     */
    public function addTagAction(Request $request)
    {
        $tagname = $request->query->get('tagname');
        $tag = $this->getTagEntityManager()->findOneByName($tagname);

        if (!$tag) {
            return $this->renderText(sprintf('Erreur: Cette balise "%s" n\'exist pas dans notre liste.', $tagname));
        }

        $userTag = $this->getTagEntityManager()->findByUser($tag, $this->getUser());
        if ($userTag) {
            return $this->renderText(sprintf('Erreur: Cette balise "%s" exist déjà dans votre liste.', $tagname));
        }

        $saved = $this->getTagEntityManager()->saveUserTag($tag, $this->getUser());

        return $this->renderText(sprintf('La balise "%s" a été ajouté à votre liste.', $tagname));
    }

    /**
     * @Route("/api/typeahead/tags/", name="_api_typeahead_tags")
     */
    public function typeheadTagsAction(Request $request)
    {
        $tags = array();

        $tagname = $request->query->get('tagname');

        if (preg_match("/[^,]+$/", trim($tagname, ","), $output)) {
            $tagname = trim($output[0]);
        }

        $tagsORM = $this->getTagEntityManager()->findStartsWith($tagname);
        foreach ($tagsORM as $tag) {
            $tags[$tag->getId()] = $tag->getName();
        }

        return $this->renderJson($tags);
    }

    /**
     * @Route("/api/typeahead/titles/", name="_api_typeahead_titles")
     */
    public function typeheadTitlesAction(Request $request)
    {
        $titles = array();

        if ($title = $request->query->get('q')) {
            $limit = $this->getParameter('typeahead_limit');

            foreach ($this->getPostEntityManager()->findTitlesStartsWith($title) as $titleItem) {
                $titles[] = String::truncate($titleItem['title'], 25);
            }
        }

        return $this->renderJson($titles);
    }

    /**
     * @Route("/api/post/{id}/tags/", name="_api_post_show")
     * @ParamConverter("post", class="OSCommonBundle:Post")
     */
    public function tagsAction(Post $post)
    {
        $tags = array();
        foreach ($post->getTag() as $tag) {
            $tags[$tag->getId()] = $tag->getName();
        }

        return $this->renderJson($tags);
    }

    /**
     * @Route("/api/tag/{id}/count-uses/", name="_api_tag_countuses")
     * @ParamConverter("tag", class="OSCommonBundle:Tag")
     */
    public function countUsingTagAction(Tag $tag)
    {
        return $this->renderText($this->getTagEntityManager()->getCountUsing($tag));
    }

    /**
     * @Route("/api/posts/tags/", name="_api_posts_tags")
     */
    public function postsTagsAction(Request $request)
    {
        $tags = array();
        if ($request->query->has('posts')) {
            foreach ($request->query->get('posts') as $postId) {
                $tagsAsJson = $this->forward('OSPostBundle:Api:tags', array('id' => $postId))->getContent();

                $tags = array_merge($tags, (array)json_decode($tagsAsJson));
            }
        }

        return $this->renderJson($tags);
    }

    /**
     * @Route("/api/tags/most-populate/", name="_api_tags_populate")
     */
    public function tagsPopulateAction()
    {
        $param = $this->getParameter('min_tags_popular');
        $limit = $this->getParameter('limit_tags_popular');

        $tags = array();
        $objectsTag = $this->getTagEntityManager()->getPopulate($param, $limit);
        foreach ($objectsTag as $tag) {
            $tags[$tag->getId()] = $tag->getName();
        }

        return $this->renderJson($tags);
    }

    /**
     * @Route("/api/tags/count-popular/", name="_api_tags_countpopulate")
     */
    public function countPopularTagsAction()
    {
        $param = $this->getParameter('min_tags_popular');

        return $this->renderText($this->getTagEntityManager()->getCountPopulate($param));
    }

    /**
     * @Route("/api/post/{id}/count-answers/", name="_api_post_countanswers")
     * @ParamConverter("post", class="OSCommonBundle:Post")
     */
    public function countAnswersAction(Post $post)
    {
        return $this->renderText($this->getPostEntityManager()->getCountPostAnswers($post));
    }

    /**
     * @Route("/api/post/{id}/count-rating/{flag}/", name="_api_post_countrating")
     * @ParamConverter("post", class="OSCommonBundle:Post")
     */
    public function countRatingAction(Post $post, $flag)
    {
        if ($flag == 'diff') {
            $up = $post->getRatingUp();
            $down = $post->getRatingDown();

            $text = $up - $down;

            if ($text > 0) {
                $text = '+' . $text;
            }
        } elseif ($flag == 'all') {
            $up = $post->getRatingUp();
            $down = $post->getRatingDown();

            $text = $up + $down;
        } else {
            $text = Rating::RATING_UP == $flag ? $post->getRatingUp() : $post->getRatingDown();
        }

        return $this->renderText($text);
    }

    /**
     * @Route("/api/user/{id}/count-post/{postType}/", name="_api_countposts_user")
     * @ParamConverter("user", class="OSCommonBundle:User")
     */
    public function countPostsUserAction(User $user, $postType)
    {
        return $this->renderText($this->getPostEntityManager()->getCountPostsByUser($user, $postType));
    }

    /**
     * @Route("/api/user/{id}/count-bookmark/", name="_api_user_countbookmark")
     * @ParamConverter("user", class="OSCommonBundle:User")
     */
    public function countBookmarkByUserAction(User $user)
    {
        return $this->renderText($user->getBookmark()->count());
    }

    /**
     * @Route("/api/user/{id}/tags/", name="_api_user_tags")
     * @ParamConverter("user", class="OSCommonBundle:User")
     */
    public function userTagsAction(User $user)
    {
        $tags = array();
        $objectsTag = $this->getTagEntityManager()->getAllByUser($user);
        foreach ($objectsTag as $tag) {
            $tags[$tag->getId()] = $tag->getName();
        }

        return $this->renderJson($tags);
    }

    /**
     * @Route("/api/user/{id}/count-tags/", name="_api_user_counttags")
     * @ParamConverter("user", class="OSCommonBundle:User")
     */
    public function countUserTagsAction(User $user)
    {
        return $this->renderText($this->getTagEntityManager()->getCountByUser($user));
    }

    /**
     * @Route("/api/user/{id}/count-rating/{postType}/{flag}/", name="_api_countrating_user")
     * @ParamConverter("user", class="OSCommonBundle:User")
     */
    public function countUserRatingAction(User $user, $postType, $flag)
    {
        $count = $this->getPostEntityManager()->getCountRatingByUser($user, $postType, $flag);

        return $this->renderText($count);
    }

    /**
     * @Route("/api/posts/count-popular/", name="_api_posts_countpopular")
     */
    public function countPopularAction()
    {
        $count = $this
            ->getPostEntityManager()
            ->getQuestionsQuery(array('(p.ratingUp - p.ratingDown) > 0'), array(), true);

        return $this->renderText($count);
    }

    /**
     * @Route("/api/user/{id}/count-myquestions/", name="_api_user_count_myquestions")
     * @ParamConverter("user", class="OSCommonBundle:User")
     */
    public function countQuestionsByUserAction(User $user)
    {
        $count = $this
            ->getPostEntityManager()
            ->getQuestionsQuery(array('p.enabled = 1', sprintf('p.user = %d', $user->getId())), array(), true);

        return $this->renderText($count);
    }

    /**
     * @Route("/api/user/{id}/count-myanswers/", name="_api_user_count_myquestions")
     * @ParamConverter("user", class="OSCommonBundle:User")
     */
    public function countAnswersByUserAction(User $user)
    {
        $count = $this
            ->getPostEntityManager()
            ->getQuestionsQuery(array('p.enabled = 1', 'USERID_ANSWERS' => $user->getId()), array(), true);

        return $this->renderText($count);
    }

    /**
     * @Route("/api/user/{id}/count-mynetwork/", name="_api_user_count_myquestions")
     * @ParamConverter("user", class="OSCommonBundle:User")
     */
    public function countMyNetworkByUserAction(User $user)
    {
        $count = $this
            ->getPostEntityManager()
            ->getQuestionsQuery(array('p.enabled = 1', 'USERID_NETWORK' => $user->getId()), array(), true);

        return $this->renderText($count);
    }

    /**
     * @Route("/api/posts/count/", name="_api_posts_count")
     */
    public function countAction()
    {
        return $this->renderText($this->getPostEntityManager()->getCountQuestions());
    }

    /**
     * @return PostEntityManager
     */
    public function getPostEntityManager()
    {
        return $this->container->get('os_post.entity.manager');
    }

    /**
     * @return TagEntityManager
     */
    public function getTagEntityManager()
    {
        return $this->container->get('os_tag.entity.manager');
    }
}
