<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OS\PostBundle\Provider;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use OS\CommonBundle\Provider\AbstractProvider;
use OS\CommonBundle\Entity\Post;

/**
 * @author oaurdisoft
 */
class PostProvider extends AbstractProvider
{

    /**
     * @todo implement this method
     * 
     * @param Post $post
     */
    public function canSave(Post $post)
    {
        if (false) {
            throw new AccessDeniedException();
        }
    }
}
