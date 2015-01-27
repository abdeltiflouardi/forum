<?php

namespace OS\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="rating")
 */
class Rating
{
    const RATING_UP = 1;
    const RATING_DOWN = 0;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="rating")
     */
    private $post;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $flag;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->flag = 1;
        $this->createdAt = new \DateTime();
    }

    /**
     * Set flag
     *
     * @param integer $flag
     * @return Rating
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    
        return $this;
    }

    /**
     * Get flag
     *
     * @return integer 
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Rating
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set post
     *
     * @param \OS\CommonBundle\Entity\Post $post
     * @return Rating
     */
    public function setPost(\OS\CommonBundle\Entity\Post $post)
    {
        $this->post = $post;
    
        return $this;
    }

    /**
     * Get post
     *
     * @return \OS\CommonBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set user
     *
     * @param \OS\CommonBundle\Entity\User $user
     * @return Rating
     */
    public function setUser(\OS\CommonBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \OS\CommonBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
