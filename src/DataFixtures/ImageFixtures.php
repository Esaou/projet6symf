<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Image;
use App\Repository\FigureRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;

class ImageFixtures extends Fixture  implements DependentFixtureInterface
{
    private ObjectManager $manager;

    private array $images = [
        '180.jpg',
        '360.jpg',
        '540.jpg',
        '720.jpg',
        '810.jpg',
        'indy.jpg',
        'japanair.jpg',
        'mute.jpg',
        'nosegrab.jpg',
        'sad.jpg',
        'seatbelt.jpg',
        'stalefish.jpg',
        'tailgrab.jpg',
        'trick15.jpg',
        'trick16.jpg',
        'trick17.jpg',
        'trick18.jpg',
        'trick19.jpg',
        'trick20.jpg',
        'truckdriver.jpg'
    ];

    private string $pathToDirectory;

    public function __construct() {
        $this->pathToDirectory = "images/figures/";
    }

    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        // Create 20 images

        $this->generateImages(20);

        $manager->flush();
    }

    public function generateImages(int $number) {

        for ($i=0;$i < $number;$i++) {

            /** @var Figure $figure */
            $figure = $this->getReference("figure".$i);

            $photos = $figure->getImages();

            $main = true;

            foreach ($photos as $photo) {
                if ($photo->getMain() === true) {
                    $main = false;
                }
            }

            $image = new Image();
            $image
                ->setFigure($figure)
                ->setFilename($this->pathToDirectory . $this->images[$i])
                ->setMain($main);

            $this->addReference("image$i",$image);

            $this->manager->persist($image);

        }
    }

    public function getDependencies()
    {
        return [
            FigureFixtures::class,
        ];
    }
}
