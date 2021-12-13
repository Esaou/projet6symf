<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Image;
use App\Repository\FigureRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

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
        'truckdriver.jpg',
        '1801.jpg',
        '3601.jpg',
        '5401.jpg',
        '7201.jpg',
        '8101.jpg',
        'indy1.jpg',
        'japanair1.jpg',
        'mute1.jpg',
        'nosegrab1.jpg',
        'sad1.jpg',
        'seatbelt1.jpg',
        'stalefish1.jpg',
        'tailgrab1.jpg',
        'trick151.jpg',
        'trick161.jpg',
        'trick171.jpg',
        'trick181.jpg',
        'trick191.jpg',
        'trick201.jpg',
        'truckdriver1.jpg'
    ];

    private string $pathToDirectory;

    public function __construct()
    {
        $this->pathToDirectory = "images/figures/";
    }

    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        // Create 20 images

        $this->generateImages(40);

        $manager->flush();
    }

    public function generateImages(int $number)
    {

        for ($i=0;$i < $number;$i++) {

            $main = true;

            if ($i >= 20) {
                $main = false;
            } else {
                /**
                 * @var Figure $figure
                */
                $figure = $this->getReference("figure".$i);
            }

            if ($i >= 20) {
                $filename = $this->images[mt_rand(0, 19)];
            } else {
                $filename = $this->images[$i];
            }

            $image = new Image();
            $image
                ->setFigure($figure)
                ->setFilename($this->pathToDirectory . $filename)
                ->setMain($main);

            $this->addReference("image$i", $image);

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
