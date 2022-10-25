<?php

namespace App\State;

use App\Entity\User;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface
{
    private $_passwordEncoder;
    private $_entityManager;

    public function __construct(
        UserPasswordHasherInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $this->_passwordEncoder = $passwordEncoder;
        $this->_entityManager = $entityManager;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
       match ($operation){
              new Post() => $this->processPost($data),
              new Put() => $this->processPut($data),
         };
    }

    private function processPost(User $user): void
    {
        $user->setPassword($this->_passwordEncoder->hashPassword($user, $user->getPlainPassword()));
        $user->eraseCredentials();
        $this->_entityManager->persist($user);
        $this->_entityManager->flush();
    }
    
    private function processPut(User $user): void
    {
        $user->setUpdateAt(new \DateTimeImmutable());
        if(!empty($user->getPlainPassword())){
            $user->setPassword($this->_passwordEncoder->hashPassword($user, $user->getPlainPassword()));
        $user->eraseCredentials();
        }
        $this->_entityManager->persist($user);
        $this->_entityManager->flush();
    }

}