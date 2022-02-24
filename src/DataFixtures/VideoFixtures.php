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
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/aGPiQ47ahsE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
    ];

    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        // Create 20 videos

        $this->generateVideos(40);

        $manager->flush();
    }

    public function generateVideos(int $number)
    {

        for ($v=0;$v < $number;$v++) {

            if ($v >= 20) {
                /**
 * @var Figure $figure 
*/
                $figure = $this->getReference("figure".mt_rand(0, 19));
            } else {
                /**
 * @var Figure $figure 
*/
                $figure = $this->getReference("figure".$v);
            }

            if ($v >= 20) {
                $url = $this->videos[mt_rand(0, 19)];
            } else {
                $url = $this->videos[$v];
            }

            $video = new Video();
            $video
                ->setFigure($figure)
                ->setUrl($url);

            $this->addReference("video$v", $video);

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
