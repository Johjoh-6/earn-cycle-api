<?php

namespace App\EventListener;

use App\Entity\User;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class LoginSuccessListener
{
    public function onLoginSuccess(JWTCreatedEvent $event): void
    {
        // add one day of authorization
        $expiration = new \DateTime('+1 day');

        $user = $event->getUser();
        $payload = $event->getData();
        if (!$user instanceof User) {
            return;
        }
        // Add information to user payload
        $payload['userId'] = $user->getId();
        $payload['apiPath'] = '/api/users/' . $user->getId();

        $payload['exp'] = $expiration->getTimestamp();
        $event->setData($payload);
        $header        = $event->getHeader();
        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }
}