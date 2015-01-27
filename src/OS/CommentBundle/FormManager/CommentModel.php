<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OS\CommentBundle\FormManager;

use OS\CommonBundle\FormManager\AbstractFormModel;

/**
 * Description of CommentModel
 *
 * @author ouardisoft
 */
class CommentModel extends AbstractFormModel
{
    private $comment;

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        $this->getEntity()->setComment($comment);

        return $this;
    }
}
