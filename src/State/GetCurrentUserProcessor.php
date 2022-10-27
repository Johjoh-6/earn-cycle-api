<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Rubbish;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GetCurrentUserProcessor implements ProcessorInterface
{
    private $_entityManager;
    private $_security;
    private $_tokenStorage;
    private $_jwtManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        TokenStorageInterface $tokenStorage,
        JWTTokenManagerInterface $jwtManager
    ) {
        $this->_jwtManager = $jwtManager;
        $this->_entityManager = $entityManager;
        $this->_security = $security;
        $this->_tokenStorage = $tokenStorage;
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
        $token = $this->_tokenStorage->getToken();

        // if (!$token) {
        //     return null;
        // }

        $user = $token->getUser();
        
        $decodedJwtToken = $this->_jwtManager->decode($this->_tokenStorageInterface->getToken());
        $userId = $decodedJwtToken['userId'];
        

        return $this->_entityManager->getRepository(User::class)->find($userId);

        // $user = $this->_security->getUser();

        // if (!$user instanceof User) {
        //     return null;
        // }
        // return $user;
    }
}
