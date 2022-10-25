<?php

namespace App\State;

use App\Entity\User;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface
{
    private $_passwordEncoder;
    private $_entityManager;

    public function __construct(
        UserPasswordHasherInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        RequestStack $request,
    ) {
        $this->_passwordEncoder = $passwordEncoder;
        $this->_entityManager = $entityManager;
        $this->_request = $request->getCurrentRequest();
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if($data instanceof User){
       match ($this->_request->getMethod()) {
            "POST" => $this->processPost($data),
            "PUT" => $this->processPut($data),
            default => "",
             
         };
        }
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