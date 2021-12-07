<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMS = [
        [
            'title' => 'Walking Dead',
            'summary' => 'Le policier Rick Grimes se réveille après un long coma. Il découvre avec effarement que le monde, ravagé par une épidémie, est envahi par les morts-vivants.',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BZmFlMTA0MmUtNWVmOC00ZmE1LWFmMDYtZTJhYjJhNGVjYTU5XkEyXkFqcGdeQXVyMTAzMDM4MjM0._V1_.jpg',
            'category' => 'Horreur',
            'actors' => [''],
        ],
        [
            'title' => 'The Haunting Of Hill House',
            'summary' => 'Plusieurs frères et sœurs qui, enfants, ont grandi dans la demeure qui allait devenir la maison hantée la plus célèbre des États-Unis, sont contraints de se réunir pour finalement affronter les fantômes de leur passé.',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BMTU4NzA4MDEwNF5BMl5BanBnXkFtZTgwMTQxODYzNjM@._V1_SY1000_CR0,0,674,1000_AL_.jpg',
            'category' => 'Horreur',
            'actors' => [''],
        ],
        [
            'title' => 'American Horror Story',
            'summary' => 'A chaque saison, son histoire. American Horror Story nous embarque dans des récits à la fois poignants et cauchemardesques, mêlant la peur, le gore et le politiquement correct.',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BODZlYzc2ODYtYmQyZS00ZTM4LTk4ZDQtMTMyZDdhMDgzZTU0XkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_SY1000_CR0,0,666,1000_AL_.jpg',
            'category' => 'Horreur',
            'actors' => [''],
        ],
        [
            'title' => 'Love Death And Robots',
            'summary' => 'Un yaourt susceptible, des soldats lycanthropes, des robots déchaînés, des monstres-poubelles, des chasseurs de primes cyborgs, des araignées extraterrestres et des démons assoiffés de sang : tout ce beau monde est réuni dans 18 courts métrages animés déconseillés aux âmes sensibles.',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BMTc1MjIyNDI3Nl5BMl5BanBnXkFtZTgwMjQ1OTI0NzM@._V1_SY1000_CR0,0,674,1000_AL_.jpg',
            'category' => 'Horreur',
            'actors' => [''],
        ],
        [
            'title' => 'Penny Dreadful',
            'summary' => 'Dans le Londres ancien, Vanessa Ives, une jeune femme puissante aux pouvoirs hypnotiques, allie ses forces à celles d Ethan, un garçon rebelle et violent aux allures de cowboy, et de Sir Malcolm, un vieil homme riche aux ressources inépuisables. Ensemble, ils combattent un ennemi inconnu, presque invisible, qui ne semble pas humain et qui massacre la population.',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BNmE5MDE0ZmMtY2I5Mi00Y2RjLWJlYjMtODkxODQ5OWY1ODdkXkEyXkFqcGdeQXVyNjU2NjA5NjM@._V1_SY1000_CR0,0,695,1000_AL_.jpg',
            'category' => 'Horreur',
            'actors' => [''],
        ],
        [
            'title' => 'Fear The Walking Dead',
            'summary' => 'La série se déroule au tout début de l épidémie relatée dans la série-mère The Walking Dead et se passe dans la ville de Los Angeles, et non à Atlanta. Madison est conseillère dans un lycée de Los Angeles. Depuis la mort de son mari, elle élève seule ses deux enfants : Alicia, excellente élève qui découvre les premiers émois amoureux, et son grand frère Nick qui a quitté la fac et a sombré dans la drogue.',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BYWNmY2Y1NTgtYTExMS00NGUxLWIxYWQtMjU4MjNkZjZlZjQ3XkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_SY1000_CR0,0,666,1000_AL_.jpg',
            'category' => 'Horreur',
            'actors' => [''],
        ],
        [
            'title' => 'L\'Attaque des Titans',
            'summary' => 'L’Attaque des Titans (進撃の巨人, Shingeki no Kyojin?, litt. Le Titan assaillant, souvent abrégé SnK) est un shōnen manga écrit et dessiné par Hajime Isayama. Il est prépublié entre septembre 2009 et avril 2021 dans le magazine Bessatsu Shōnen Magazine puis compilé en trente-quatre volumes reliés par l’éditeur Kōdansha. La version française est publiée en intégralité par Pika Édition dans la collection seinen entre juin 2013 et octobre 2021. L’histoire tourne autour du personnage d’Eren Jäger dans un monde où l’humanité vit entourée d’immenses murs pour se protéger de créatures gigantesques, les Titans. Le récit raconte le combat mené par l’humanité pour reconquérir son territoire, en éclaircissant les mystères liés à l’apparition des Titans, du monde extérieur et des évènements précédant la construction des murs.',
            'poster' => 'https://img.betaseries.com/pAk2i0jhPrK9cK3WYv5xhGwN0MM=/400x600/smart/https%3A%2F%2Fpictures.betaseries.com%2Ffonds%2Fposter%2F0183f272b2f972d358be9d2267fe6f1a.jpg',
            'category' => 'Animation',
            'actors' => [''],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (static::PROGRAMS as $key => $data) {
            $program = (new Program())
                ->setTitle($data['title'])
                ->setSummary($data['summary'])
                ->setPoster($data['poster']);

            $categoryKey = array_search($data['category'], CategoryFixtures::CATEGORIES);
            $program->setCategory($this->getReference('category_' . $categoryKey));

            foreach (array_keys(ActorFixtures::ACTORS) as $actorKey) {
                $program->addActor($this->getReference('actor_' . $actorKey));
            }

            $manager->persist($program);
            $this->addReference('program_' . $key, $program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
