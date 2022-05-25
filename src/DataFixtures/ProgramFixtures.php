<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    
    public function load(ObjectManager $manager)
    {
        $programs = [
            [
                'title' => 'Arcane',
                'synopsis' => 'Série animée qui se déroule dans l\'univers de la franchise de jeu vidéo "League of Legends". Intitulée "Arcane".',
                'poster' => 'https://fr.web.img5.acsta.net/c_310_420/pictures/21/11/02/11/12/2878509.jpg',
                'category' => $this->getReference('category_Animation'),
            ],
            [
                'title' => 'Lucifer',
                'synopsis' => 'Lucifer est une série télévisée américaine créée par Tom Kapinos, adaptée du personnage de bandes dessinées créée par Neil Gaiman, Sam Kieth et Mike Dringenberg, publié chez Vertigo DC Comics',
                'poster' => 'https://th.bing.com/th/id/OIP.rLC4s7kE2kCN3ADPexJmOwHaDj?w=290&h=167&c=7&r=0&o=5&dpr=1.25&pid=1.7',
                'category' => $this->getReference('category_Fntastique'),
            ],
            [
                'title' => 'The Walking Dead',
                'synopsis' => 'The Walking Dead est une série télévisée américaine créée par la société HBO. Elle est sortie en 2010 et est la première série télévisée américaine à avoir été diffusée en France.',
                'poster' => 'https://images-na.ssl-images-amazon.com/images/I/51Z%2BX%2BH%2BHIL._SY445_.jpg',
                'category' => $this->getReference('category_Action'),
            ],
            [
                'title' => 'Chair de Poule',
                'synopsis' => 'est une série télévisée canadienne en 74 épisodes de 21 minutes, créée par Deborah Forte',
                'poster' => 'https://www.cinealliance.fr/wp-content/uploads/2016/02/chairdepoule-1152x1536.jpg ',
                'category' => $this->getReference('category_Horreur'),
            ],
            [
                'title' => 'Game of Thrones',
                'synopsis' => 'Game of Thrones est un jeune garçon qui aime les jeux vidéo. Il est aussi un fan de la série The Walking Dead. Il aime également les films de science-fiction et les séries télévisées.',
                'poster' => 'https://th.bing.com/th/id/OIP.vIDvamBmbzOkV9C2v7dDSwHaEK?w=288&h=180&c=7&r=0&o=5&dpr=1.25&pid=1.7',
                'category' => $this->getReference('category_Action'),
            ],

            [
                'title' => 'Hunter x Hunter',
                'synopsis' => 'Abandonné par son père, un aventurier et chasseur de primes, le jeune Gon décide à de partir pour devenir un Hunter.',
                'poster' => 'https://th.bing.com/th/id/OIP.NgscepXsXUePuE7rqyp3qgHaFl?w=264&h=198&c=7&r=0&o=5&dpr=1.25&pid=1.7',
                'category' => $this->getReference('category_Aventure'),
            ],

 
        ];


        foreach ($programs as $key => $programname) {


            $program = new Program();
            $program->setTitle($programname['title']);
            $program->setSynopsis($programname['synopsis']);
            $program->setPoster($programname['poster']);
            $program->setCategory($programname['category']);
            $manager->persist($program);
            $this->addReference('program_' . $programname['title'], $program);
            

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
