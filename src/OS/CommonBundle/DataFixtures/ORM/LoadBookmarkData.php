<?php

namespace OS\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadBookmarkData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('user');
        $post = $this->getReference('post');

        $user->addBookmark($post);
        $post->addBookmarkedBy($user);

        $manager->persist($user);
        $manager->persist($post);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     * 
     * @return int
     */
    public function getOrder()
    {
        return 7;
    }
}
