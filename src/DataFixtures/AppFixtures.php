<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @var \Faker\Factory
     */
    private $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadBlogPosts($manager);
    }
    public function loadBlogPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $blogPost = new BlogPost;
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublishedAt($this->faker->dateTimeThisYear);
            $blogPost->setContent($this->faker->realText());
            $blogPost->setAuthor($this->faker->name);
            $blogPost->setSlug($this->faker->slug);
            $this->setReference("blog_post_$i", $blogPost);
            $manager->persist($blogPost);
        }
        $manager->flush();
    }
}
