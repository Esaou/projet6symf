<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{

    private array $categories = [
        'Grabs',
        'Rotation verticale',
        'Rotation horizontale',
        'Switch',
        'Fly style'
    ];

    private ObjectManager $manager;

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        // Create 5 categories

        $this->generateCategories(5);

        $manager->flush();
    }

    public function generateCategories(int $number)
    {

        for ($c=0;$c < $number;$c++) {

            $slug = $this->slugger->slug($this->categories[$c]);

            $category = new Category();
            $category
                ->setName($this->categories[$c])
                ->setSlug($slug);

            $this->addReference("category$c", $category);

            $this->manager->persist($category);

        }
    }

}
