<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OS\PostBundle\FormManager;

use OS\CommonBundle\FormManager\AbstractFormHandler;
use Symfony\Component\Form\Form;
use OS\CommonBundle\Entity\Post;

/**
 * Description of PostHandler
 *
 * @author ouardisoft
 */
class PostHandler extends AbstractFormHandler
{

    /**
     * 
     * @param \Symfony\Component\Form\Form $form
     * @param string[] $options
     * 
     * @return Post
     */
    public function processValidForm(Form $form, array $options = array())
    {
        $model = $form->getData();
        $entity = $model->getEntity();

        //canSave?
        $this->provider->canSave($entity);

        // Define post type
        if (!array_key_exists('Parent', $options)) {
            $postTypeId = Post::POST_QUESTION_ID;
        } else {
            $postTypeId = Post::POST_ANSWER_ID;
            $entity->setParent($options['Parent']);
        }

        if (array_key_exists('defaultPostStatus', $options)) {
            $entity->setEnabled($options['defaultPostStatus']);
        }

        $postType = $this->manager->getPostType($postTypeId);
        $entity->setPostType($postType);

        // Define creation user
        $entity->setUser($this->provider->getUser());

        $this->manager->save($entity);

        return $entity;
    }
}
