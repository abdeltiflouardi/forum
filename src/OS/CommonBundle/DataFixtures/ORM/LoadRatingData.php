<?php

namespace OS\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OS\CommonBundle\Entity\Rating;

class LoadRatingData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $post = $this->getReference('post');
        $user = $this->getReference('user');

        $rating = new Rating();
        $rating->setPost($post);
        $rating->setUser($user);

        $manager->persist($rating);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     * 
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }
}
