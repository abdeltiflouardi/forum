<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OS\PostBundle\EntityManager;

use OS\CommonBundle\EntityManager\AbstractEntityManager;
use OS\CommonBundle\Entity\Tag;
use OS\CommonBundle\Entity\User;

/**
 * Description of TagManager
 *
 * @author ouardisoft
 */
class TagManager extends AbstractEntityManager
{

    /**
     * 
     * @param string[] $where
     * @param string[] $order
     * 
     * @return Query
     */
    public function getQuery($where = array(), $orders = array())
    {
        $builder = $this->repository->createQueryBuilder('t');

        foreach ($where as $value) {
            $builder->andWhere($value);
        }

        foreach ($orders as $field => $order) {
            $builder->orderBy($field, $order);
        }

        return $builder->getQuery();
    }

    /**
     * @param int $limit Limit of items (Tag)
     * 
     * @return Tag[]
     */
    public function getPopulate($min = 0, $limit = 5)
    {
        $builder = $this->repository->createQueryBuilder('t');
        $builder->where('t.count >= :count')
                ->setParameter('count', $min)

                ->orderBy('t.count', 'DESC')

                ->setMaxResults($limit);

        return $builder->getQuery()->getResult();
    }

    /**
     * 
     */
    public function getCountPopulate($min = 0)
    {
        $builder = $this->repository->createQueryBuilder('t');
        $builder->select($builder->expr()->count('t'));

        $builder->where('t.count >= :count')
                ->setParameter('count', $min);

        return $builder->getQuery()->getSingleScalarResult();
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\Tag $tag
     */
    public function getCountUsing(Tag $tag)
    {
        return $tag->getPost()->count();
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\User $user
     * @param integer $limit
     * 
     * @return Tag[]
     */
    public function getAllByUser(User $user, $limit = 5)
    {
        $orderfields = array('t.id', 't.name', 't.count', 'DESC', 'ASC');

        $count = $user->getTag()->count();
        if ($count > $limit) {
            $offset = rand(0, $count - $limit);
        } else {
            $offset = 0;
        }

        $builder = $this->repository->createQueryBuilder('t');
        $builder->select()
                ->join('t.user', 'u')

                ->where('u.id = :user')
                ->setParameter('user', $user)

                ->orderBy($orderfields[rand(0, 2)], $orderfields[rand(3, 4)])

                ->setMaxResults($limit)
                ->setFirstResult($offset);

        return $builder->getQuery()->getResult();
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\User $user
     * @param integer $limit
     * 
     * @return integer
     */
    public function getCountByUser(User $user)
    {
        return $user->getTag()->count();
    }

    /**
     * 
     * @param string $tagname
     * @return Tag
     */
    public function findOneByName($tagname)
    {
        return $this->repository->findOneByName($tagname);
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\Tag $tag
     * @param \OS\CommonBundle\Entity\User $user
     * @return Tag|null
     */
    public function findByUser(Tag $tag, User $user)
    {
        $builder = $this->repository->createQueryBuilder('t');
        $builder->join('t.user', 'u');

        $builder
                ->where('t.id = :tag')
                ->setParameter('tag', $tag->getId())

                ->andWhere('u.id = :user')
                ->setParameter('user', $user->getId());
        try {
            return $builder->getQuery()->getOneOrNullResult();
        } catch (Doctrine\ORM\NonUniqueResultException $ex) {
            return null;
        }
        
    }

    public function findStartsWith($name)
    {
        $builder = $this->repository->createQueryBuilder('t');
        $builder->where($builder->expr()->like('t.name', $builder->expr()->literal('%' . $name . '%')));

        return $builder->getQuery()->getResult();
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\Tag $tag
     * @param \OS\CommonBundle\Entity\User $user
     */
    public function saveUserTag(Tag $tag, User $user)
    {
        $tag->addUser($user);
        $user->addTag($tag);

        $this->save($user, false);
        $this->save($tag);
    }
}
