<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OS\PostBundle\FormManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use OS\CommonBundle\Validator\Constraints\Tag;

/**
 * Description of PostType
 *
 * @author ouardisoft
 */
class PostType extends AbstractType
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

        // Form Title field
        $builder->add(
            'title',
            'text',
            array(
                'attr' => array('class' => 'span12'),
                'constraints' => array(new NotBlank(), new Length(array('min' => 3)))
             )
        );

        // Form Content Field
        $builder->add(
            'content',
            'textarea',
            array(
                'label' => false,
                'attr' => array('class' => 'span12', 'rows' => 5),
                'constraints' => array(new NotBlank(), new Length(array('min' => 3)))
            )
        );

        // Form Tag Field
        $builder->add(
            'tag',
            'tag',
            array(
                'label' => 'Tags',
                'attr' => array('class' => 'span12 add-tag', 'autocomplete' => 'off', 'data-separator' => ','),
                'constraints' => array(
                    new NotBlank(),
                    new Tag(array('limit' => 5, 'length' => 16, 'alphanum' => true))
                 )
            )
        );

        // Form Submit Button
        $builder->add('add', 'submit', array('label' => 'Add Question', 'attr' => array('class' => 'btn btn-primary')));
    }

    /**
     * 
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('required' => false));
    }

    /**
     * 
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        // If answer mode
        if (!isset($view->children['title'])) {
            $child = $view->children['add'];
            $child->vars['label'] = 'Publish Answer';
        }

        parent::finishView($view, $form, $options);
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'post';
    }
}
