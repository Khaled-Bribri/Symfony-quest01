<?php

namespace App\DataFixtures;
use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 10000; $i++) {

            $episode = new Episode();
            $episode->setTitle($faker->sentence(3, true));
            $episode->setNumber($i);
            $episode->setSynopsis($faker->paragraphs(3, true));
            $episode->setslug($faker->slug);
            $episode->setSeason($this->getReference('season_'. $faker->numberBetween(0, 99)));
            $episode->setProgram($this->getReference('program_'. $faker->numberBetween(0, 19)));
            $this->setReference('episode_' .$i, $episode);
            $manager->persist($episode);
        }

        $manager->flush();
       }

       public function getDependencies()
       {
           // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
           return [
             SeasonFixtures::class,
           ];
       }
}

