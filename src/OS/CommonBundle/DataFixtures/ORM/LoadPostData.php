<?php

namespace OS\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OS\CommonBundle\Entity\Post;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Question
         */
        $post = new Post();
        $post->setTitle('Lorem Ipsum is simply dummy text');
        $post->setContent($this->getQuestion());
        $post->setRatingUp(1);

        $post->setUser($this->getReference('user'));
        $post->setPostType($this->getReference('questionType'));

        foreach (LoadDataInterface::$tags as $key => $value) {
            if (!is_int($key)) {
                $post->addTag($this->getReference('tag' . $value));
                $manager->persist($this->getReference('tag' . $value));
            }
        }

        $manager->persist($post);
        $manager->flush();

        /**
         * Answer
         */
        $answer = new Post();
        $answer->setContent($this->getAnswer());

        $answer->setParent($post);
        $answer->setUser($this->getReference('user'));
        $answer->setPostType($this->getReference('answerType'));

        $manager->persist($answer);
        $manager->flush();

        $this->addReference('post', $post);
    }

    /**
     * 
     */
    public function getQuestion()
    {
        return <<<EOQ
        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
            when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                
        It has survived not only five centuries, but also the leap into electronic typesetting,
            remaining essentially unchanged. 
                
        It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
            and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
EOQ;
    }

    /**
     * 
     */
    public function getAnswer()
    {
        return <<<EOA
        There are many variations of passages of Lorem Ipsum available, 
            but the majority have suffered alteration in some form, by injected humour, 
                or randomised words which don't look even slightly believable.
         If you are going to use a passage of Lorem Ipsum, 
             you need to be sure there isn't anything embarrassing hidden in the middle of text. 
         
         All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, 
             making this the first true generator on the Internet. 
                 
         It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, 
             to generate Lorem Ipsum which looks reasonable. 
                 
        The generated Lorem Ipsum is therefore always free from repetition, injected humour,
            or non-characteristic words etc.
EOA;
    }

    /**
     * {@inheritDoc}
     * 
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}
