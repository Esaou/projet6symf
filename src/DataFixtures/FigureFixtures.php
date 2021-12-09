<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureFixtures extends Fixture implements DependentFixtureInterface
{

    private array $figures = [
        [
          'name' => 'Rotation 180',
          'description' => 'Effectuer un demi-tour sur soi-même en l\'air',
        ],
        [
            'name' => 'Rotation 360',
            'description' => 'Effectuer un tour sur soi-même en l\'air',
        ],
        [
            'name' => 'Rotation 540',
            'description' => 'Effectuer un tour et demi sur soi-même en l\'air',
        ],
        [
            'name' => 'Rotation 720',
            'description' => 'Effectuer deux tours sur soi-même en l\'air',
        ],
        [
            'name' => 'Rotation 810',
            'description' => 'Effectuer deux tours et demi sur soi-même en l\'air',
        ],
        [
            'name' => 'Indy',
            'description' => 'Jeter les bras en arrière et tendre les jambes',
        ],
        [
            'name' => 'Japanair',
            'description' => 'Faire un salto en avant',
        ],
        [
            'name' => 'Mute',
            'description' => 'Attraper la planche à deux mains an l\'air',
        ],
        [
            'name' => 'Nose grab',
            'description' => 'Attraper l\'avant de la planche en l\'air',
        ],
        [
            'name' => 'Sad',
            'description' => 'Attraper les deux extremités de la planche en l\'air',
        ],
        [
            'name' => 'Seat belt',
            'description' => 'Faire des rotations inversés avant arrière',
        ],
        [
            'name' => 'Stale fish',
            'description' => 'Faire un salto arriere en tenant la planche',
        ],
        [
            'name' => 'Tail grab',
            'description' => 'Faire un salto avant en effectuant une rotation de 180 degrès',
        ],
        [
            'name' => 'True nose',
            'description' => 'Faire un salto arrière en effectuant une rotation de 360 degrès',
        ],
        [
            'name' => 'Inverse door',
            'description' => 'Faire un salto avant en tenant les deux extremités de la planche',
        ],
        [
            'name' => 'Truck driver',
            'description' => 'Faire une rotation de 90 degrès en tenant le centre de la planche',
        ],
        [
            'name' => 'Misty',
            'description' => 'Slider sur un rail',
        ],
        [
            'name' => 'Misty top',
            'description' => 'Faire une rotation de 810 degrès sur un rail',
        ],
        [
            'name' => 'Switch',
            'description' => 'Faire un slide sur un rail en sens inverse',
        ],
        [
            'name' => 'Switch backflip',
            'description' => 'Faire un salto arrière inversé',
        ],
    ];

    private ObjectManager $manager;

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger) {
        $this->slugger = $slugger;
    }


    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        // Generate 15 figures

        $this->generateFigures(20);

        $manager->flush();
    }

    public function generateFigures(int $number) {
        for ($f=0;$f < $number;$f++) {

            $slug = $this->slugger->slug($this->figures[$f]['name'],'_');

            /** @var User $user */
            $user = $this->getReference("user".mt_rand(0,4));

            /** @var Category $category */
            $category = $this->getReference("category".mt_rand(0,4));

            $figure = new Figure();
            $figure
                ->setName($this->figures[$f]['name'])
                ->setDescription($this->figures[$f]['description'])
                ->setCreatedAt(new \DateTimeImmutable("now + $f day"))
                ->setSlug($slug)
                ->setCategory($category)
                ->setUser($user);

            $this->addReference("figure$f",$figure);

            $this->manager->persist($figure);

        }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class
        ];
    }

}
