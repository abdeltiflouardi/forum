<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OS\PostBundle\FormManager;

use OS\CommonBundle\FormManager\AbstractFormModel;
use Doctrine\Common\Collections\ArrayCollection;
use OS\CommonBundle\Entity\Post;
use OS\CommonBundle\Entity\Tag;

/**
 * Description of PostModel
 *
 * @author ouardisoft
 */
class PostModel extends AbstractFormModel
{
    /**
     *
     * @var Post
     */
    private $parent;

    /**
     *
     * @var string
     */
    private $title;

    /**
     *
     * @var string
     */
    private $content;

    /**
     *
     * @var ArrayCollection
     */
    private $tag = array();

    /**
     * 
     * @return Post
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * 
     * @param \OS\CommonBundle\Entity\Post $parent
     * @return \OS\PostBundle\FormManager\PostModel
     */
    public function setParent(Post $parent)
    {
        $this->parent = $parent;
        $this->getEntity()->setParent($parent);

        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * 
     * @param string $title
     * @return \OS\PostBundle\FormManager\PostModel
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->getEntity()->setTitle($title);

        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 
     * @param string $content
     * @return \OS\PostBundle\FormManager\PostModel
     */
    public function setContent($content)
    {
        $this->content = $content;
        $this->getEntity()->setContent($content);

        return $this;
    }

    /**
     * 
     * @return Tag[]
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * 
     * @param Tag[] $tag
     * @return \OS\PostBundle\FormManager\PostModel
     */
    public function setTag(ArrayCollection $tag = null)
    {
        foreach ((array)$tag as $item) {
            $this->addTag($item);
        }

        return $this;
    }

    /**
     * 
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $this->tag[] = $tag;
        $this->getEntity()->addTag($tag);

        return $this;
    }

    /**
     * 
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tag->removeElement($tag);

        return $this;
    }

    /**
     * 
     * @return Post
     */
    public function getEntity()
    {
        return parent::getEntity();
    }
}
