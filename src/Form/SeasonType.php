<?php

namespace App\Form;

use App\Entity\Season;
use App\Service\Slugify;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function __construct(private Slugify $slugify)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('year')
            ->add('description')
            ->add('program', null, ['choice_label' => 'title'])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var Season $season */
                $season = $event->getData();
                $season->setSlug($this->slugify->generate($season->getNumber()));
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
