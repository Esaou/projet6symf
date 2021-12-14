<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{

    private array $messages = [
        'Très beau skill !',
        'Très beau saut !',
        'Quelle figure !',
        'Je suis surpris de la facilité ...',
        'J\'ai hâte d\'essayer ce trick !',
        'Comment réaliser cette figure simplement ?',
        'Je suis débutant, cette figure est parfaite !',
        'Je n\'aime pas cette figure',
        'Comment ne pas aimer ce trick ?',
        'Est il possible d\'apprendre cette figure en un an ?',
        'Merci des informations !',
        'Je suis très satisfait de ce site.',
        'Je trouve tout ce qu\'il me faut sur ce site web, merci !',
        'Je laisse un avis très positif pour cette figure ! Au top'
    ];

    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        // Create 70 messages

        $this->generateMessages(70);

        $manager->flush();
    }

    public function generateMessages(int $number)
    {

        for ($m=0;$m < $number;$m++) {

            /**
 * @var User $user 
*/
            $user = $this->getReference("user".mt_rand(0, 4));

            /**
 * @var Figure $figure 
*/
            $figure = $this->getReference("figure".mt_rand(0, 19));

            $message = new Message();
            $message
                ->setFigure($figure)
                ->setUser($user)
                ->setContent($this->messages[mt_rand(0, 13)])
                ->setCreatedAt(new \DateTimeImmutable("now - $m day"));

            $this->addReference("message$m", $message);

            $this->manager->persist($message);

        }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            FigureFixtures::class,
        ];
    }
}
