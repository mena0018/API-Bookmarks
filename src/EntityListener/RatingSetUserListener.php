<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Rating;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

#[AsEntityListener(
    event: Events::prePersist,
    entity: Rating::class
)]
class RatingSetUserListener
{
    public function __construct(private Security $security)
    {
    }

    public function prePersist(Rating $rating): void
    {
        $currentUser = $this->security->getUser();

        if (!$rating->getUser() || null !== $currentUser) {
            /* @var $currentUser User */
            $rating->setUser($currentUser);
        }
    }
}
