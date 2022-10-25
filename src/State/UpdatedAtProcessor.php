<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;

class UpdatedAtProcessor implements ProcessorInterface
{
    private $_entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->_entityManager = $entityManager;
    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $data->setUpdateAt(new \DateTimeImmutable());
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }
}
