<?php

namespace OS\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity
 */
class Tag
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=true)
     */
    private $count;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tag")
     */
    private $post;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="tag")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->post = new \Doctrine\Common\Collections\ArrayCollection();
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->count = 1;
    }
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Tag
     */
    public function setCount($count)
    {
        $this->count = $count;
    
        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Add post
     *
     * @param \OS\CommonBundle\Entity\Post $post
     * @return Tag
     */
    public function addPost(\OS\CommonBundle\Entity\Post $post)
    {
        $this->post[] = $post;
    
        return $this;
    }

    /**
     * Remove post
     *
     * @param \OS\CommonBundle\Entity\Post $post
     */
    public function removePost(\OS\CommonBundle\Entity\Post $post)
    {
        $this->post->removeElement($post);
    }

    /**
     * Get post
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPost()
    {
        return $this->post;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Add user
     *
     * @param \OS\CommonBundle\Entity\User $user
     * @return Tag
     */
    public function addUser(\OS\CommonBundle\Entity\User $user)
    {
        $this->user[] = $user;
    
        return $this;
    }

    /**
     * Remove user
     *
     * @param \OS\CommonBundle\Entity\User $user
     */
    public function removeUser(\OS\CommonBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }
}
