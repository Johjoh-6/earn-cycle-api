<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Rubbish;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class GetCurrentUserProcessor implements ProcessorInterface
{
    private $_entityManager;
    private $_security;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->_entityManager = $entityManager;
        $this->_security = $security;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if($data instanceof Rubbish){
            $data->setCreatedBy($this->getUser());
            $this->_entityManager->persist($data);
            $this->_entityManager->flush();
        }
    }

    protected function getUser(): ?User
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }
}
