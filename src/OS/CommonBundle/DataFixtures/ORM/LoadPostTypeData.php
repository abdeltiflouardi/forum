<?php

namespace OS\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OS\CommonBundle\Entity\PostType;

class LoadPostTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach (array('Question', 'Answer', 'Moderator') as $k => $value) {
            $postType = new PostType();
            $postType->setName($value);

            $manager->persist($postType);
            $manager->flush();

            if ($k == 0) {
                $questionType = $postType;
            } elseif ($k == 1) {
                $answerType = $postType;
            }
        }

        $this->addReference('questionType', $questionType);
        $this->addReference('answerType', $answerType);
    }

    public function getOrder()
    {
        return 2;
    }
}
