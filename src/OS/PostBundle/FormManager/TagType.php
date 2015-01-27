<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OS\PostBundle\FormManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OS\PostBundle\FormManager\TagTransformer;

/**
 * Description of PostType
 *
 * @author ouardisoft
 */
class TagType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * 
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Form Method
        $builder->addModelTransformer(new TagTransformer($this->om));
    }

    /**
     * 
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'tag';
    }
}
