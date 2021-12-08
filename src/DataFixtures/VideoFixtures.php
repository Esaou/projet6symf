<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VideoFixtures extends Fixture  implements DependentFixtureInterface
{

    private array $videos = [
        'https://www.youtube.com/watch?v=SQyTWk7OxSI',
        'https://www.youtube.com/watch?v=gbHU6J6PRRw',
        'https://www.youtube.com/watch?v=Dafmcn0UR5g',
        'https://www.youtube.com/watch?v=WbT3CyDI8Qo',
        'https://www.youtube.com/watch?v=8WAnK76q2zo',
        'https://www.youtube.com/watch?v=I08wCQ5WvIQ',
        'https://www.youtube.com/watch?v=BKouLnxY-lA',
        'https://www.youtube.com/watch?v=I-IO_f5QG8M',
        'https://www.youtube.com/watch?v=5MH8S0zWl_Y',
        'https://www.youtube.com/watch?v=PddO-NcZBtU',
        'https://www.youtube.com/watch?v=monyw0mnLZg',
        'https://www.youtube.com/watch?v=PxhfDec8Ays',
        'https://www.youtube.com/watch?v=SFYYzy0UF-8',
        'https://www.youtube.com/watch?v=EN_GE9sGLL0',
        'https://www.youtube.com/watch?v=6ghmAMFHL3c',
        'https://www.youtube.com/watch?v=dSZ7_TXcEdM',
        'https://www.youtube.com/watch?v=oI-umOzNBME',
        'https://www.youtube.com/watch?v=Z1gCwhmTV7A',
        'https://www.youtube.com/watch?v=7cz-dB-6GTI',
        'https://www.youtube.com/watch?v=AMH992l_nRg',
    ];

    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        // Create 20 videos

        $this->generateVideos(20);

        $manager->flush();
    }

    public function generateVideos(int $number) {

        for ($v=0;$v < $number;$v++) {

            /** @var Figure $figure */
            $figure = $this->getReference("figure".mt_rand(0,19));

            $video = new Video();
            $video
                ->setFigure($figure)
                ->setUrl($this->videos[$v]);

            $this->addReference("video$v",$video);

            $this->manager->persist($video);

        }
    }

    public function getDependencies()
    {
        return [
            FigureFixtures::class,
        ];
    }
}
