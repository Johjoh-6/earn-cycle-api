<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    public function __construct (UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        // Admin
        $admin = new User();
        $admin->setEmail($_ENV['ADMIN_EMAIL']);
        $admin->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $admin->setFName('Admin');
        $admin->setLName('EarnCycle');
        $admin->setPhone('0000000000');
        $admin->setAdress('20 rue de la paix rouen 76000');
        $admin->setLevel(100);
        $admin->setWallet(1000);
        $admin->setNickname("Admin");
        
        // hash password 
        $password = $this->userPasswordHasherInterface->hashPassword($admin, $_ENV['ADMIN_PASSWORD']);
        $admin->setPassword($password);
        $admin->eraseCredentials();
        $manager->persist($admin);

        // category list
        $categoryList = ['Plastic', 'Glass', 'Cardboard', 'Metal', 'Compost', 'Wood', 'Waste', 'Clothes', 'Recycling', 'Other'];
        foreach($categoryList as $category){
            $cat = new Category();
            $cat->setName($category);
            $manager->persist($cat);
        }

        $manager->flush();
    }
}
