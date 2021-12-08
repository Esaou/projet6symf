<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Message;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($c=1;$c <= 5;$c++) {

            // Create 5 categories

            $category = new Category();
            $category
                ->setName("Categorie numéro $c")
                ->setSlug("categorie_numero_$c");

            $manager->persist($category);

            // Create 5 images for users

            $imageUser = new Image();
            $imageUser
                ->setFilename("https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460__340.png")
                ->setMain(false);

            $manager->persist($imageUser);

            // Create 5 users

            $user = new User();
            $user
                ->setUsername("User$c")
                ->setIsValid(true)
                ->setEmail("test$c@test.com")
                ->setPassword('12345')
                ->setSlug(uniqid())
                ->setPhoto($imageUser);

            $manager->persist($user);
            $manager->flush();

            // Create 5 figures for each category

            for ($f=1;$f <= 5;$f++) {
                $figure = new Figure();
                $figure
                    ->setName("Figure numéro $f")
                    ->setDescription(
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                 sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                 Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                 nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                 reprehenderit in voluptate velit esse cillum dolore eu fugiat
                 nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                 sunt in culpa qui officia deserunt mollit anim id est laborum."
                    )
                    ->setCreatedAt(new \DateTimeImmutable("now + $f day"))
                    ->setSlug("figure_numero_$f")
                    ->setCategory($category)
                    ->setUser($user);

                $manager->persist($figure);

                // Create 2 images for each figure

                for ($i=1;$i <= 2;$i++) {

                    $main = false;

                    if ($i != 1){
                        $main = true;
                    }

                    $image = new Image();
                    $image
                        ->setFigure($figure)
                        ->setFilename("https://cdn.futura-sciences.com/buildsv6/images/wide1920/c/0/b/c0b199d73b_120515_lexique-snowboard.jpg")
                        ->setMain($main);

                    $manager->persist($image);
                }

                // Create 2 videos for each figure

                for ($v=1;$v <= 2;$v++) {
                    $video = new Video();
                    $video
                        ->setFigure($figure)
                        ->setUrl("https://www.youtube.com/watch?v=Ey5elKTrUCk");

                    $manager->persist($video);
                }

                // Create 10 messages for each figure

                for ($m=1;$m <= 10;$m++) {
                    $message = new Message();
                    $message
                        ->setFigure($figure)
                        ->setUser($user)
                        ->setContent("Commentaire de test numéro $i")
                        ->setCreatedAt(new \DateTimeImmutable("now + $m day"));

                    $manager->persist($message);
                }
            }
        }

        $manager->flush();
    }
}
