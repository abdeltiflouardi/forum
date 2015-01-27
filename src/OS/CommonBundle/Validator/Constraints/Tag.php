<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OS\CommonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Description of Tag
 * 
 * @Annotation
 * 
 * @author ouardisoft
 */
class Tag extends Constraint
{
    public $limitMessage = 'This tags should contain {{ limit }} elements or less.';
    public $lengthMessage = 'The tag "{{ tag }}" is too long. It should have {{ limit }} characters or less.';
    public $alphanumMessage = 'The tag "{{ tag }}" contains an illegal character: it can only contain letters/numbers.';
    public $separatorMessage = 'The tag "{{ tag }}" contains an illegal character: the separator is "{{ separator }}"';
    public $limit = 10;
    public $length = 10;
    public $alphanum = false;
    public $separator = ',';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
