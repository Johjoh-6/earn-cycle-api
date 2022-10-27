<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Rubbish;
use Doctrine\ORM\EntityManagerInterface;
use CrEOF\Spatial\PHP\Types\Geometry\Point;

class AddGeoSpacialProcessor implements ProcessorInterface
{
    private $_entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->_entityManager = $entityManager;
    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
            $point = new Point($data->getLongitude(), $data->getLatitude());
            var_dump($point);
            dd($point);
            $data->setCordinates($point);
            $this->_entityManager->persist($data);
            $this->_entityManager->flush();
    }
}
