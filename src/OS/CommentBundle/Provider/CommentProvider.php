<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OS\CommentBundle\Provider;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use OS\CommonBundle\Provider\AbstractProvider;
use OS\CommonBundle\Entity\Comment;

/**
 * @author oaurdisoft
 */
class CommentProvider extends AbstractProvider
{

    /**
     * @todo implement this method
     * 
     * @param Comment $post
     */
    public function canSave(Comment $comment)
    {
        if (false) {
            throw new AccessDeniedException();
        }
    }
}
