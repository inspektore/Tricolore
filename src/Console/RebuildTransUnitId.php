<?php
namespace Tricolore\Console;

use Tricolore\Foundation\Application;
use Tricolore\Config\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class RebuildTransUnitId extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('trans:rebuild-trans-unit-id')
            ->setDescription('Rebuilds all trans-unit ID starts from 1');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filesystem = new Filesystem();
        $locale = Config::getParameter('trans.locale');

        $translation_files = [
            Application::getInstance()->createPath(sprintf('app:translations:%s:messages.xliff', $locale)),
            Application::getInstance()->createPath(sprintf('app:translations:%s:validators.xliff', $locale))
        ];

        $output->writeln('Loaded files:');

        foreach ($translation_files as $files) {
            $output->writeln(sprintf('<comment>%s</comment>', $files));

            $xml_source = file_get_contents($files);
            $xml = new \SimpleXMLElement($xml_source);
            $i = 1;

            foreach ($xml->file->body->{'trans-unit'} as $attributes) {
                $attributes->attributes()->id = $i++;
            }

            $filesystem->dumpFile($files, $xml->asXML());
        }

        $output->writeln('');
        $output->writeln('<info>All trans-unit identificators are successfully rebuilded.</info>');
    }
}
