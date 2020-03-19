<?php
# src/Event/EasyAdminSubscriber.php
namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Entity\Label;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'easy_admin.pre_new' => ['preNew'],
            'easy_admin.pre_update' => ['preUpdate'],
        );
    }

    public function preNew(GenericEvent $event)
    {
    $entity = $event->getSubject();
    }

    public function preUpdate(GenericEvent $event)
    {
    $entity = $event->getSubject();
    }


}