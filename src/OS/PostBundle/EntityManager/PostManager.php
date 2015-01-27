<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OS\PostBundle\EntityManager;

use OS\CommonBundle\EntityManager\AbstractEntityManager;
use OS\CommonBundle\Entity\PostType;
use OS\CommonBundle\Entity\Post;
use OS\CommonBundle\Entity\User;
use OS\CommonBundle\Entity\Rating;
use Doctrine\ORM\Query;

/**
 * Description of PostManager
 *
 * @author ouardisoft
 */
class PostManager extends AbstractEntityManager
{

    /**
     * @param string[] $where
     * @param string[] $order
     * 
     * @return Post[]
     */
    public function getPosts($where = array(), $order = array(), $offset = 0, $limit = 20)
    {
        return array();
    }

    /**
     * 
     * @param string[] $where
     * @param string[] $order
     * 
     * @return Query
     */
    public function getQuestionsQuery($where = array(), $orders = array(), $returnCount = false)
    {
        $builder = $this->repository->createQueryBuilder('p');

        if ($returnCount) {
            $builder->select($builder->expr()->count('p'));
        }

        $builder->where('p.postType = :post_type_id')
                ->setParameter('post_type_id', Post::POST_QUESTION_ID);

        // Select By tag name
        if (isset($where['TAGNAME'])) {
            $tagname = $where['TAGNAME'];
            unset($where['TAGNAME']);

            $builder->join('p.tag', 't')
                    ->andWhere('t.name LIKE :tagname')
                    ->setParameter('tagname', $tagname);
        }

        // Select By bookmark
        if (isset($where['USERID_BOOKMARK'])) {
            $userid = $where['USERID_BOOKMARK'];
            unset($where['USERID_BOOKMARK']);

            $builder->join('p.bookmarkedBy', 'b')
                    ->andWhere('p.user = :user')
                    ->setParameter('user', $userid);
        }

        // Select my answers
        if (isset($where['USERID_ANSWERS'])) {
            $userid = $where['USERID_ANSWERS'];
            unset($where['USERID_ANSWERS']);

            $builder2 = $this->repository->createQueryBuilder('p2');
            $builder->andWhere(
                $builder->expr()->in(
                    'p.id',
                    $builder2->select('pr2.id')
                             ->join('p2.parent', 'pr2')
                             ->where('p2.postType = 2')
                             ->andWhere('p2.user = ' . $userid)
                             ->getDQL()
                )
            );
        }

        // Select Quesntions of my friends
        if (isset($where['USERID_NETWORK'])) {
            $userid = $where['USERID_NETWORK'];
            unset($where['USERID_NETWORK']);

            $builder2 = $this->em->getRepository('OSCommonBundle:User')->createQueryBuilder('u');
            $builder->andWhere(
                $builder->expr()->in(
                    'p.user',
                    $builder2->select('mf.id')
                             ->join('u.myFriends', 'mf')
                             ->where('u.id = ' . $userid)
                             ->getDQL()
                )
            );
        }

        foreach ($where as $key => $value) {
            if (preg_match('/^\d+$/', $key)) {
                $builder->andWhere($value);
            } else {
                $builder->andWhere($key);

                // Parse vars
                if (preg_match("/:([a-z]+)/", $key, $output)) {
                    $param = $output[1];
                }

                $builder->setParameter($param, $value);
            }
        }

        foreach ($orders as $field => $order) {
            $builder->orderBy($field, $order);
        }

        $query = $builder->getQuery();

        if ($returnCount) {
            return $query->getSingleScalarResult();
        } else {
            return $query;
        }
    }

    /**
     * 
     * @param string[] $where
     * @param string[] $order
     * @return Query
     */
    public function getPostAnswersQuery(Post $post, $where = array(), $orders = array())
    {
        $builder = $this->repository->createQueryBuilder('p');
        $builder->where('p.parent = :parent')
                ->setParameter('parent', $post->getId())

                ->andWhere('p.postType = :post_type_id')
                ->setParameter('post_type_id', 2);

        foreach ($where as $value) {
            $builder->andWhere($value);
        }

        foreach ($orders as $field => $order) {
            $builder->orderBy($field, $order);
        }

        return $builder->getQuery();
    }

    /**
     * 
     * @param integer $id
     * @return Post
     */
    public function findPost($id)
    {
        return $this->repository->find($id);
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\Post $post
     * @return integer
     */
    public function getCountPostAnswers(Post $post)
    {
        $builder = $this->repository->createQueryBuilder('p');

        return $builder->select($builder->expr()->count('p'))

                ->where('p.postType = :post_type_id')
                ->setParameter('post_type_id', Post::POST_ANSWER_ID)

                ->andWhere('p.parent = :parent_id')
                ->setParameter('parent_id', $post->getId())

                ->getQuery()
                ->getSingleScalarResult();
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\Post $post
     * @param string $flag
     * 
     * @return integer
     */
    public function getCountRating(Post $post, $flag)
    {
        $builder = $this->repository->createQueryBuilder('p');

        return $builder->select($builder->expr()->count('p'))
                ->join('p.rating', 'r')

                ->where('p.id = :id')
                ->setParameter('id', $post->getId())

                ->andWhere('r.flag = :flag')
                ->setParameter('flag', $flag)

                ->getQuery()
                ->getSingleScalarResult();
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\Post $post
     * @param string $flag
     * 
     * @return integer
     */
    public function getCountRatingByUser(User $user, $postType, $flag)
    {
        $builder = $this->repository->createQueryBuilder('p');

        return $builder->select($builder->expr()->count('p'))
                ->join('p.rating', 'r')

                ->where('r.user = :user')
                ->setParameter('user', $user->getId())

                ->andWhere('r.flag = :flag')
                ->setParameter('flag', $flag)

                ->andWhere('p.postType = :postType')
                ->setParameter('postType', $postType)

                ->getQuery()
                ->getSingleScalarResult();
    }

    /**
     * 
     * @return integer
     */
    public function getCountQuestions()
    {
        $builder = $this->repository->createQueryBuilder('p');

        return $builder->select($builder->expr()->count('p'))

                ->where('p.postType = :post_type_id')
                ->setParameter('post_type_id', Post::POST_QUESTION_ID)

                ->andWhere('p.enabled = :enabled')
                ->setParameter('enabled', 1)

                ->getQuery()
                ->getSingleScalarResult();
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\User $user
     * @param type $postType
     * 
     * @return integer
     */
    public function getCountPostsByUser(User $user, $postType = null)
    {
        $builder = $this->repository->createQueryBuilder('p');
        $builder->select($builder->expr()->count('p'));

        if (is_numeric($postType)) {
            $builder->where('p.postType = :post_type_id')->setParameter('post_type_id', $postType);
        }

        $builder->andWhere('p.user = :user')->setParameter('user', $user->getId());

        return $builder->getQuery()->getSingleScalarResult();
    }

    /**
     * @return Post
     */
    public function getLastPost()
    {
        return $this->repository->findOneBy(array('postType' => Post::POST_QUESTION_ID), array('createdAt' => 'DESC'));
    }

    /**
     * 
     * @param integer $id
     * @return PostType
     */
    public function getPostType($id)
    {
        return $this->em->find('OSCommonBundle:PostType', (int)$id);
    }

    /**
     * 
     * @param string $title
     * 
     * @return string[]
     */
    public function findTitlesStartsWith($title, $limit = 5)
    {
        $builder = $this->repository->createQueryBuilder('p');
        $builder->select('p.title');

        $builder->where($builder->expr()->like('p.title', $builder->expr()->literal('%' . $title . '%')));
        $builder->setMaxResults($limit);

        return $builder->getQuery()->getArrayResult();
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\Post $post
     * @param \OS\CommonBundle\Entity\User $user
     * @param integer $flag
     */
    public function saveRating(Post $post, User $user, $flag)
    {
        $postUser = $post->getUser();

        if (Rating::RATING_UP == $flag) {
            $post->setRatingUp($post->getRatingUp() + 1);

            $postUser->setScore($postUser->getScore() + 5);
        } else {
            $post->setRatingDown($post->getRatingDown() + 1);

            $postUser->setScore($postUser->getScore() - 5);
        }

        $rating = new Rating();
        $rating->setPost($post);
        $rating->setUser($user);
        $rating->setFlag($flag);

        $this->save($post, false);
        $this->save($postUser, false);
        $this->save($rating);
    }
}
