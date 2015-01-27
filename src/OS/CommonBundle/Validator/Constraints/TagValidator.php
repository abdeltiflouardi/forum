<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OS\CommonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Description of TagValidator
 * 
 * @author ouardisoft
 */
class TagValidator extends ConstraintValidator
{

    /**
     * 
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $tags = array();
        foreach ($value as $tag) {
            $name = $tag->getName();

            if (strlen($name) > $constraint->length) {
                $this->context->addViolation(
                    $constraint->lengthMessage,
                    array('{{ limit }}' => $constraint->length, '{{ tag }}' => $name)
                );
            }

            if ($constraint->alphanum && !preg_match('/^[a-z0-9\.]+$/i', $name, $matches)) {
                $this->context->addViolation($constraint->alphanumMessage, array('{{ tag }}' => $name));
            }

            if (preg_match('/[a-z]+[^a-z0-9]+[a-z]+/i', $name, $matches)) {
                $this->context->addViolation(
                    $constraint->separatorMessage,
                    array('{{ tag }}' => $name, '{{ separator }}' => $constraint->separator)
                );
            }

            $tags[] = $name;
        }

        if (count(array_unique($tags)) > $constraint->limit) {
            $this->context->addViolation($constraint->limitMessage, array('{{ limit }}' => $constraint->limit));
        }
    }
}
