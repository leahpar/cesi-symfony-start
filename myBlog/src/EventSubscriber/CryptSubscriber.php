<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Service\CryptService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;

class CryptSubscriber implements EventSubscriberInterface
{

    private CryptService $cryptService;

    public function __construct(CryptService $cryptService)
    {
        $this->cryptService = $cryptService;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::postLoad,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }

        /** @var User $entity */
        $entity->setNumeroSecu($this->cryptService->encrypt($entity->getNumeroSecu()));
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }

        /** @var User $entity */
        $entity->setNumeroSecu($this->cryptService->encrypt($entity->getNumeroSecu()));
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof User) {
            /** @var User $entity */
            $entity->setNumeroSecu($this->cryptService->decrypt($entity->getNumeroSecu()));
        }
    }

}
