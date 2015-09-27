<?php
namespace Tricolore\Console;

use Tricolore\Foundation\Application;
use Tricolore\Config\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class CacheClear extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:clear-cache')
            ->setDescription('Clear all cache files');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filesystem = new Filesystem();
        $finder = new Finder();

        $directories = $finder
            ->directories()
            ->in(Application::createPath('storage'));

        $output->writeln('Detecting cache folders...');

        if (count($directories) === 0) {
            return $output->writeln('<info>No cache folders detected. Aborting.</info>');
        }

        foreach ($directories as $directory) {
            $output->writeln(sprintf('<comment>%s</comment>', $directory->getRealpath()));

            $cache_folders[] = $directory->getRealpath();
        }

        $filesystem->remove($cache_folders);

        $output->writeln('<info>Caches cleared.</info>');
    }
}
