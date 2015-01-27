<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OS\CommonBundle\FormManager;

use Symfony\Component\Form\Form;

/**
 * Description of AbstractFormManager
 *
 * @author ouardisoft
 */
abstract class AbstractFormManager
{
    /**
     *
     * @var AbstractFormFactory
     */
    protected $factory;

    /**
     *
     * @var AbstractFormHandler
     */
    protected $handler;

    /**
     * 
     * @param \OS\CommonBundle\FormManager\AbstractFormFactory $factory
     * @param \OS\CommonBundle\FormManager\AbstractFormHandler $handler
     */
    public function __construct(AbstractFormFactory $factory, AbstractFormHandler $handler)
    {
        $this->factory = $factory;
        $this->handler = $handler;
    }

    /**
     * 
     * @return AbstractFormFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * 
     * @return AbstractFormHandler
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @return Form
     */
    public function createForm(array $options = array())
    {
        return $this->factory->create($options);
    }
}
