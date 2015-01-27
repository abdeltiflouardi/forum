<?php

namespace OS\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity
 */
class Post
{
    const POST_QUESTION_ID = 1;
    const POST_ANSWER_ID = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean $enabled
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="view_count", type="integer", nullable=true)
     */
    private $viewCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating_up", type="integer", nullable=true)
     */
    private $ratingUp;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating_down", type="integer", nullable=true)
     */
    private $ratingDown;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="bookmark", cascade={"persist"})
     */
    private $bookmarkedBy;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="post", cascade={"persist"})
     * @ORM\JoinTable(name="post_tag",
     *   joinColumns={
     *     @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *   }
     * )
     */
    private $tag;

    /**
     * @var \Post
     *
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \PostType
     *
     * @ORM\ManyToOne(targetEntity="PostType", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="post_type_id", referencedColumnName="id")
     * })
     */
    private $postType;

    /**
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="post")
     */
    private $rating;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();

        $this->enabled = 1;
        $this->viewCount = 0;
        $this->ratingDown = 0;
        $this->ratingUp = 0;

        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Post
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set viewCount
     *
     * @param integer $viewCount
     * @return Post
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;
    
        return $this;
    }

    /**
     * Get viewCount
     *
     * @return integer 
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * 
     */
    public function incViewCount()
    {
        $this->viewCount++;

        return $this;
    }

    /**
     * Add tag
     *
     * @param \OS\CommonBundle\Entity\Tag $tag
     * @return Post
     */
    public function addTag(\OS\CommonBundle\Entity\Tag $tag)
    {
        $this->tag[] = $tag;
    
        $tag->setCount($tag->getCount() + 1);

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \OS\CommonBundle\Entity\Tag $tag
     */
    public function removeTag(\OS\CommonBundle\Entity\Tag $tag)
    {
        $this->tag->removeElement($tag);
    }

    /**
     * Get tag
     *
     * @return Tag[]
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set parent
     *
     * @param \OS\CommonBundle\Entity\Post $parent
     * @return Post
     */
    public function setParent(\OS\CommonBundle\Entity\Post $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \OS\CommonBundle\Entity\Post 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set user
     *
     * @param \OS\CommonBundle\Entity\User $user
     * @return Post
     */
    public function setUser(\OS\CommonBundle\Entity\User $user = null)
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

    /**
     * Set postType
     *
     * @param \OS\CommonBundle\Entity\PostType $postType
     * @return Post
     */
    public function setPostType(\OS\CommonBundle\Entity\PostType $postType = null)
    {
        $this->postType = $postType;
    
        return $this;
    }

    /**
     * Get postType
     *
     * @return \OS\CommonBundle\Entity\PostType 
     */
    public function getPostType()
    {
        return $this->postType;
    }

    /**
     * Add rating
     *
     * @param \OS\CommonBundle\Entity\Rating $rating
     * @return Post
     */
    public function addRating(\OS\CommonBundle\Entity\Rating $rating)
    {
        $this->rating[] = $rating;
    
        return $this;
    }

    /**
     * Remove rating
     *
     * @param \OS\CommonBundle\Entity\Rating $rating
     */
    public function removeRating(\OS\CommonBundle\Entity\Rating $rating)
    {
        $this->rating->removeElement($rating);
    }

    /**
     * Get rating
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set ratingUp
     *
     * @param integer $ratingUp
     * @return Post
     */
    public function setRatingUp($ratingUp)
    {
        $this->ratingUp = $ratingUp;
    
        return $this;
    }

    /**
     * Get ratingUp
     *
     * @return integer 
     */
    public function getRatingUp()
    {
        return $this->ratingUp;
    }

    /**
     * Set ratingDown
     *
     * @param integer $ratingDown
     * @return Post
     */
    public function setRatingDown($ratingDown)
    {
        $this->ratingDown = $ratingDown;
    
        return $this;
    }

    /**
     * Get ratingDown
     *
     * @return integer 
     */
    public function getRatingDown()
    {
        return $this->ratingDown;
    }

    /**
     * Add bookmarkedBy
     *
     * @param \OS\CommonBundle\Entity\User $bookmarkedBy
     * @return Post
     */
    public function addBookmarkedBy(\OS\CommonBundle\Entity\User $bookmarkedBy)
    {
        $this->bookmarkedBy[] = $bookmarkedBy;
    
        return $this;
    }

    /**
     * Remove bookmarkedBy
     *
     * @param \OS\CommonBundle\Entity\User $bookmarkedBy
     */
    public function removeBookmarkedBy(\OS\CommonBundle\Entity\User $bookmarkedBy)
    {
        $this->bookmarkedBy->removeElement($bookmarkedBy);
    }

    /**
     * Get bookmarkedBy
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBookmarkedBy()
    {
        return $this->bookmarkedBy;
    }
}
