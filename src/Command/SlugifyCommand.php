<?php

namespace App\Command;

use App\Service\Slugify;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:slugify',
    description: 'Slugify input',
)]
class SlugifyCommand extends Command
{
    public function __construct(private Slugify $slugify)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('input', InputArgument::REQUIRED, 'Text to slugify')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $input = $input->getArgument('input');
        $io->note($this->slugify->generate($input));

        return Command::SUCCESS;
    }
}
