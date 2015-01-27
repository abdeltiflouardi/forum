<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OS\CommentBundle\FormManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Description of PostType
 *
 * @author ouardisoft
 */
class CommentType extends AbstractType
{
    /**
     * 
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Form Method
        $builder->setMethod('POST');
        $builder->setAction($options['action']);

        // Form Content Field
        $builder->add(
            'comment',
            'textarea',
            array(
                'label' => false,
                'attr' => array('class' => 'span12', 'rows' => 5),
                'constraints' => array(new NotBlank(), new Length(array('min' => 3)))
            )
        );

        // Form Submit Button
        $builder->add('add', 'submit', array('label' => 'Add Comment', 'attr' => array('class' => 'btn btn-primary')));
    }

    /**
     * 
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('required' => false, 'action' => null));
        $resolver->setRequired(array('action'));
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'comment';
    }
}
