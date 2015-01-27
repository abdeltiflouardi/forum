<?php

namespace OS\CommonBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var boolean $receiveNotification
     *
     * @ORM\Column(name="receive_notification", type="boolean", nullable=true)
     */
    private $receiveNotification;

    /**
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="myFriends")
     **/
    private $friendsWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="friend",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")}
     *      )
     **/
    private $myFriends;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="user", cascade={"persist"})
     * @ORM\JoinTable(name="user_tag",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *   }
     * )
     */
    private $tag;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Post", inversedBy="bookmarkedBy")
     * @ORM\JoinTable(name="bookmark",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     *   }
     * )
     */
    private $bookmark;

    public function __construct()
    {
        parent::__construct();

        $this->score = 1;
        $this->receiveNotification = 0;

        $this->friendsWithMe = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myFriends = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add friendsWithMe
     *
     * @param \OS\CommonBundle\Entity\User $friendsWithMe
     * @return User
     */
    public function addFriendsWithMe(\OS\CommonBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe[] = $friendsWithMe;

        return $this;
    }

    /**
     * Remove friendsWithMe
     *
     * @param \OS\CommonBundle\Entity\User $friendsWithMe
     */
    public function removeFriendsWithMe(\OS\CommonBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe->removeElement($friendsWithMe);
    }

    /**
     * Get friendsWithMe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFriendsWithMe()
    {
        return $this->friendsWithMe;
    }

    /**
     * Add myFriends
     *
     * @param \OS\CommonBundle\Entity\User $myFriends
     * @return User
     */
    public function addMyFriend(\OS\CommonBundle\Entity\User $myFriends)
    {
        $this->myFriends[] = $myFriends;
    
        return $this;
    }

    /**
     * Remove myFriends
     *
     * @param \OS\CommonBundle\Entity\User $myFriends
     */
    public function removeMyFriend(\OS\CommonBundle\Entity\User $myFriends)
    {
        $this->myFriends->removeElement($myFriends);
    }

    /**
     * Get myFriends
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMyFriends()
    {
        return $this->myFriends;
    }

    /**
     * Add tag
     *
     * @param \OS\CommonBundle\Entity\Tag $tag
     * @return User
     */
    public function addTag(\OS\CommonBundle\Entity\Tag $tag)
    {
        $this->tag[] = $tag;
    
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
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set score
     *
     * @param integer $score
     * @return User
     */
    public function setScore($score)
    {
        $this->score = $score;
    
        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Add bookmark
     *
     * @param \OS\CommonBundle\Entity\Post $bookmark
     * @return User
     */
    public function addBookmark(\OS\CommonBundle\Entity\Post $bookmark)
    {
        $this->bookmark[] = $bookmark;
    
        return $this;
    }

    /**
     * Remove bookmark
     *
     * @param \OS\CommonBundle\Entity\Post $bookmark
     */
    public function removeBookmark(\OS\CommonBundle\Entity\Post $bookmark)
    {
        $this->bookmark->removeElement($bookmark);
    }

    /**
     * Get bookmark
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBookmark()
    {
        return $this->bookmark;
    }

    /**
     * Set receiveNotification
     *
     * @param boolean $receiveNotification
     * @return User
     */
    public function setReceiveNotification($receiveNotification)
    {
        $this->receiveNotification = $receiveNotification;
    
        return $this;
    }

    /**
     * Get receiveNotification
     *
     * @return boolean 
     */
    public function getReceiveNotification()
    {
        return $this->receiveNotification;
    }
}
