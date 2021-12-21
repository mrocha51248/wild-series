<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Season;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private Slugify $slugify)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->referenceRepository->getReferences() as $key => $program) {
            if ($program instanceof Program) {
                $program = $this->getReference($key);

                for ($i = 1; $i <= 10; ++$i) {
                    $season = (new Season())
                        ->setProgram($program)
                        ->setNumber($i)
                        ->setYear(2022)
                        ->setDescription("Saison $i de {$program->getTitle()}")
                        ->setSlug($this->slugify->generate("Saison $i"))
                    ;

                    $manager->persist($season);
                    $this->addReference('season_' . $i . '_' . $key, $season);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
