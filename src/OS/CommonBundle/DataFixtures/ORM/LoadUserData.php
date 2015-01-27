<?php

namespace OS\CommonBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OS\CommonBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $friend = new User();
        $friend->setEnabled(1);
        $friend->setEmail('root1@root.com');
        $friend->setUsername('root1');
        $friend->setRoles(array('ROLE_MODERATOR'));
        $friend->setScore(rand(1, 1000000));

        $fencoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($friend);

        $friend->setPassword($fencoder->encodePassword('root1', $friend->getSalt()));

        $manager->persist($friend);
        $manager->flush();

        $user = new User();
        $user->addMyFriend($friend);
        $user->setRoles(array('ROLE_MODERATOR'));
        $user->setEnabled(1);
        $user->setScore(rand(1, 1000000));
        $user->setEmail('root@root.com');
        $user->setUsername('root');

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user);

        $user->setPassword($encoder->encodePassword('root', $user->getSalt()));

        $manager->persist($user);
        $manager->flush();

        $this->addReference('user', $user);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
