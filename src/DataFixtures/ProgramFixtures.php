<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');


        for($i = 0; $i < 20; $i++) {


            $program = new Program();
            $program->setTitle($faker->sentence(3, true));
            $program->setSynopsis($faker->paragraphs(3, true));
            $program->setPoster($faker->imageUrl(400, 300));
            $program->setCategory($this->getReference('category_' . $faker->randomElement(['Action', 'Aventure', 'Animation', 'Fntastique', 'Horreur']) ));
            $manager->persist($program);
            $this->setReference('program_' .$i, $program);
            

        }

        $manager->flush();
    }
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          CategoryFixtures::class,
        ];
    }
}
