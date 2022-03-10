<?php

namespace App\EventListener;

use App\Entity\Figure;
use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureListener
{

    private SluggerInterface $slugger;

    private Security $security;

    public function __construct(Security $security, SluggerInterface $slugger) {
        $this->slugger = $slugger;
        $this->security = $security;
    }

    public function prePersist(Figure $entity, LifecycleEventArgs $args): void
    {

        /** @var User $user */
        $user = $this->security->getUser();

        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Figure) {
            return;
        }

        $slug = $this->slugger->slug($entity->getName());

        $entity
            ->setUser($user)
            ->setSlug($slug)
            ->setCreatedAt(new \DateTimeImmutable());
    }

    public function preUpdate(Figure $entity, LifecycleEventArgs $args): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Figure) {
            return;
        }

        $slug = $this->slugger->slug($entity->getName());

        $entity
            ->setUser($user)
            ->setSlug($slug)
            ->setUpdatedAt(new \DateTimeImmutable());
    }
}