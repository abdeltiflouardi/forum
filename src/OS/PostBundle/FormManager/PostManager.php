<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OS\PostBundle\FormManager;

use Symfony\Component\Form\Form;
use OS\CommonBundle\FormManager\AbstractFormManager;

/**
 * Description of PostManager
 *
 * @author ouardisoft
 */
class PostManager extends AbstractFormManager
{

    /**
     * 
     * @return Form
     */
    public function createAnswerForm()
    {
        $form = $this->createForm();

        // Remove unused fields in answer
        $form->remove('title');
        $form->remove('tag');

        return $form;
    }
}
