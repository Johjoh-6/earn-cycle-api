<?php

namespace App\Controller;

use ApiPlatform\Api\QueryParameterValidator\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class VoucherUserController extends AbstractController
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

    #[Route('api/user_vouchers_list', name: 'voucher_by_user', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        if($user){
            $userId = $user->getId();
            $vouchers = $this->_entityManager->getRepository(User::class)->find($userId)->getUserVouchers();
            return $this->json($vouchers);
        }
        else{
            return $this->json(["Error" => "Can't find the user"]);
        }
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
