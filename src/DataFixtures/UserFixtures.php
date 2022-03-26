<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;

class UserFixtures extends Fixture
{
    /** @var array|array[] $users */
    private array $users = [
        [
            'username' => 'Esaou',
            'email' => 'eric.test@test.com',
            'isValid' => true,
            'password' => 'Motdepassergpb1!',
            'avatar' => null,
        ],
        [
            'username' => 'Melissa38',
            'email' => 'melissa.test@test.com',
            'isValid' => true,
            'password' => 'Motdepassergpb1!',
            'avatar' => 'default.png',
        ],
        [
            'username' => 'JakeHenderson',
            'email' => 'jake.henderson@test.com',
            'isValid' => true,
            'password' => 'Motdepassergpb1!',
            'avatar' => null,
        ],
        [
            'username' => 'Jacques07',
            'email' => 'jacques07@test.com',
            'isValid' => false,
            'password' => 'Motdepassergpb1!',
            'avatar' => null,
        ],
        [
            'username' => 'HelloWorld',
            'email' => 'hello.world@test.com',
            'isValid' => false,
            'password' => 'Motdepassergpb1!',
            'avatar' => null,
        ]
    ];

    private ObjectManager $manager;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        // Create 5 users

        $this->generateUsers(5);

        $manager->flush();
    }

    public function generateUsers(int $number): void
    {

        for ($u=0;$u < $number;$u++) {

            $user = new User();

            $password = $this->passwordHasher->hashPassword($user, $this->users[$u]['password']);

            $token = null;

            if ($this->users[$u]['isValid'] === false) {
                $token = Uuid::v6();
            }

            $user
                ->setUsername($this->users[$u]['username'])
                ->setIsValid($this->users[$u]['isValid'])
                ->setEmail($this->users[$u]['email'])
                ->setPassword($password)
                ->setSlug(Uuid::v6())
                ->setToken($token)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setAvatar($this->users[$u]['avatar'])
            ;

            $this->addReference("user$u", $user);

            $this->manager->persist($user);

        }
    }
}
