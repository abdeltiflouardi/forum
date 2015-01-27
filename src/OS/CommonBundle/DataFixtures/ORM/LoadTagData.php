<?php

namespace OS\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OS\CommonBundle\Entity\Tag;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach (LoadDataInterface::$tags as $key => $value) {
            $tag = new Tag();
            $tag->setName($value);
            $tag->setCount(rand(1, 8));

            $manager->persist($tag);
            $manager->flush();

            if (!is_int($key)) {
                $this->addReference('tag' . $value, $tag);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
