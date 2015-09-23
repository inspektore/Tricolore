<?php
namespace Tricolore\Console;

use Tricolore\Foundation\Application;
use Tricolore\Config\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

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
        
        $filesystem->remove([
            Application::createPath('storage:twig'),
            Application::createPath('storage:router'),
            Application::createPath('storage:translations')
        ]);

        $output->writeln('<info>Caches cleared.</info>');
    }
}
