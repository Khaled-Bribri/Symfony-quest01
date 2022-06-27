<?php

namespace App\DataFixtures;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();
  

        for ($i=0;$i<10;$i++){

            $user = new user();
            $user->setEmail($faker->Email);
            $user->setRoles($faker->randomElement([['Super_Admin'],['Admin'],['User']]));
            $user->setPassword($this->passwordHasher->hashPassword($user,'password'));

            $manager->persist($user);

        }

        $manager->flush();

        

        
    }
}
