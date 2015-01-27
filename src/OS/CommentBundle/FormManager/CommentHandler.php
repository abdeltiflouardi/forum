<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OS\CommentBundle\FormManager;

use OS\CommonBundle\FormManager\AbstractFormHandler;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\Form\Form;
use OS\CommonBundle\Entity\Comment;

/**
 * Description of CommentHandler
 *
 * @author ouardisoft
 */
class CommentHandler extends AbstractFormHandler
{

    /**
     * 
     * @param \Symfony\Component\Form\Form $form
     * @param string[] $options
     * 
     * @return Comment
     */
    public function processValidForm(Form $form, array $options = array())
    {
        $model = $form->getData();
        $entity = $model->getEntity();

        if (!array_key_exists('post', $options)) {
            throw new MissingOptionsException('Missing "post" option.');
        }

        $entity->setPost($options['post']);

        //canSave?
        $this->provider->canSave($entity);

        // Define creation user
        $entity->setUser($this->provider->getUser());

        $this->manager->save($entity);

        return $entity;
    }
}
