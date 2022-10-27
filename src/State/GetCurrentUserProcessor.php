<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Rubbish;
use Doctrine\ORM\EntityManagerInterface;

class GetCurrentUserProcessor implements ProcessorInterface
{
    private $_entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->_entityManager = $entityManager;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
            $data->setCreatedBy($context['security']->getUser());
            $this->_entityManager->persist($data);
            $this->_entityManager->flush();
    }
}
