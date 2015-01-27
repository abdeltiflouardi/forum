<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OS\CommentBundle\EntityManager;

use OS\CommonBundle\EntityManager\AbstractEntityManager;
use OS\CommonBundle\Entity\Post;

/**
 * Description of CommentManager
 *
 * @author ouardisoft
 */
class CommentManager extends AbstractEntityManager
{
    /**
     * 
     * @param \OS\CommonBundle\Entity\Post $post
     * @return integer
     */
    public function getCountPostComments(Post $post)
    {
        $builder = $this->repository->createQueryBuilder('c');

        return $builder->select($builder->expr()->count('c'))
                ->where('c.post = :post_id')
                ->setParameter('post_id', $post->getId())
                ->getQuery()
                ->getSingleScalarResult();
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\Post $post
     * @return \OS\CommonBundle\Entity\Comment
     */
    public function getLastByPost(Post $post)
    {
        $builder = $this->repository->createQueryBuilder('c');

        $builder->where('c.post = :post_id')
                ->setParameter('post_id', $post->getId())

                ->orderBy('c.createdAt', 'DESC')

                ->setMaxResults(1);

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\Post $post
     * @return \OS\CommonBundle\Entity\Comment[]
     */
    public function getAllByPost(Post $post)
    {
        $builder = $this->repository->createQueryBuilder('c');

        $builder->where('c.post = :post_id')
                ->setParameter('post_id', $post->getId())

                ->orderBy('c.createdAt', 'DESC');

        return $builder->getQuery()->getResult();
    }
}
