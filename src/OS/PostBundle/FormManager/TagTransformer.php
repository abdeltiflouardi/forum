<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OS\PostBundle\FormManager;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use OS\CommonBundle\Entity\Tag;

/**
 * Description of PostType
 *
 * @author ouardisoft
 */
class TagTransformer implements DataTransformerInterface
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
     * Transforms an object (tags) to a string (number).
     *
     * @param  ArrayCollection|null $tags
     * @return string
     */
    public function transform($tags)
    {
        if (count($tags) == 0) {
            return "";
        }

        $tagsAsArray = array();
        foreach ($tags as $tag) {
            $tagsAsArray[] = $tag->getName();
        }

        return implode(', ', $tagsAsArray);
    }

    /**
     * Transforms a string (number) to an object (tag).
     *
     * @param  string $number
     *
     * @return ArrayCollection|null
     *
     * @throws TransformationFailedException if object (tag) is not found.
     */
    public function reverseTransform($tags)
    {
        if (!$tags) {
            return null;
        }
 
        $names = array_unique(array_filter(array_map('trim', explode(',', $tags))));

        $arrayCollection = new ArrayCollection();
        foreach ($names as $name) {
            $tag = $this->om
                ->getRepository('OSCommonBundle:Tag')
                ->findOneByName(array('name' => $name));

            if (!$tag) {
                $tag = new Tag();
                $tag->setName($name);
            }

            $arrayCollection->add($tag);
        }

        return $arrayCollection;
    }
}
