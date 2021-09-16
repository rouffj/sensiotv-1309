<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisteredSubscriber implements EventSubscriberInterface
{
    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $user = $event->getUser();
        $email = [
            'to' => $user->getEmail(),
            'subject' => sprintf('Bienvenue %s sur SensioTv', $user->getFirstName()),
            'body' => 'Nous sommes heureux de t\'avoir parmi nous' ,
        ];

        dump($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            'user_registered' => 'onUserRegistered',
        ];
    }
}
