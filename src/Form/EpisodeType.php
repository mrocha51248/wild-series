<?php

namespace App\Form;

use App\Entity\Episode;
use App\Entity\Season;
use App\Service\Slugify;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function __construct(private Slugify $slugify)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('number')
            ->add('synopsis')
            ->add('season', null, [
                'choice_label' => fn (Season $season) =>
                $season->getProgram()->getTitle() . ' / ' . $season->getNumber()
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var Episode $episode */
                $episode = $event->getData();
                $episode->setSlug($this->slugify->generate($episode->getNumber()));
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
