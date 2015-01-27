<?php

namespace OS\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserTagData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach (LoadDataInterface::$tags as $key => $value) {
            if (!is_int($key)) {
                $user = $this->getReference('user');
                $user->addTag($this->getReference('tag' . $value));

                $manager->persist($user);
                $manager->flush();
            }
        }
    }

    /**
     * {@inheritDoc}
     * 
     * @return int
     */
    public function getOrder()
    {
        return 6;
    }
}
