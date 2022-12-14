<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Rubbish;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

#[AsController]
class UserRubbishController extends AbstractController
{
    private $_entityManager;
    private $_tokenStorage;

    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
    ) {
        $this->_entityManager = $entityManager;
        $this->_tokenStorage = $tokenStorage;
    }

    #[Route('/api/rubbishes', name: 'post_rubbish', methods: ['POST'])]
    public function index(Request $request, ValidatorInterface $validator): Response
    {
        $content = $request->getContent();
        $data = json_decode($content, true);
        $rubbish = $this->createNewRubbish($data);
        $errors = $validator->validate($rubbish);
        if (count($errors) > 0) {
            return $this->json(["Error" => $errors]);
        }
        if($rubbish){
            $this->_entityManager->persist($rubbish);
            $this->_entityManager->flush();
            return $this->json(["Success" => "Rubbish add to the DB, index: " . $rubbish->getId()]);
        }
        else{
            return $this->json(["Error" => "Can't add the rubbish"]);
        }
    }

    protected function createNewRubbish($data): Rubbish
    {
        $rubbish = new Rubbish();
        $rubbish->setCountry(array_key_exists('country', $data) ? $data['country'] : '');
        $rubbish->setCity(array_key_exists('city', $data) ? $data['city'] : '');
        $rubbish->setLatitude(array_key_exists('latitude', $data) ? $data['latitude'] : '');
        $rubbish->setLongitude(array_key_exists('longitude' , $data) ? $data['longitude'] : '');
        $rubbish->setPostalCode(array_key_exists('postalCode', $data) ? $data['postalCode'] : '');
        $rubbish->setStreetName(array_key_exists('streetName', $data ) ? $data['streetName'] : '');
        $rubbish->setNbStreet(array_key_exists('nbStreet', $data ) ? $data['nbStreet'] : '');
        $rubbish->setCertified(false);
        if(is_int($data['category'])){
        $rubbish->setCategory($this->_entityManager->getRepository(Category::class)->find(['id' => $data['category']]));
        }
        else{
            $id = explode('/',$data['category']);
            $id = $id[count($id)-1];
            $rubbish->setCategory($this->_entityManager->getRepository(Category::class)->find(['id' => $id]));
        }
        $rubbish->setCreatedBy($this->getUser());  
        return $rubbish;
    }

    protected function getUser(): ?User
    {
        $token = $this->_tokenStorage->getToken();
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
