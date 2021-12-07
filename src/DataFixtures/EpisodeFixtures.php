<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->referenceRepository->getReferences() as $key => $season) {
            if ($season instanceof Season) {
                $season = $this->getReference($key);

                for ($i = 1; $i <= 12; ++$i) {
                    $episode = (new Episode())
                        ->setSeason($season)
                        ->setTitle('Episode ' . $i)
                        ->setNumber($i)
                        ->setSynopsis('Il se passe des trucs');

                    $manager->persist($episode);
                    $this->addReference('episode_' . $i . '_' . $key, $season);
                }
                $manager->persist($season);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
