<?php

namespace Vangrg\ProfanityBundle\Command;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Vangrg\ProfanityBundle\Entity\Profanity;

/**
 * Class ProfanitiesPopulateCommand
 * @package Vangrg\ProfanityBundle\Command
 */
class ProfanitiesPopulateCommand extends Command
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setName('vangrg:profanities:populate')
            ->setDescription('Load profanities into database.')
            ->addOption('connection',
                null,
                InputOption::VALUE_OPTIONAL,
                'The connection to use for this command. If empty then use default doctrine connection.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->container->get('doctrine');

        $connectionName = $input->getOption('connection');
        $em = (empty($connectionName) === true)
            ? $doctrine->getManagerForClass('Vangrg\ProfanityBundle\Entity\Profanity')
            : $doctrine->getManager($connectionName);

        $profanities = $this->container->get('vangrg_profanity.storage.default')->getProfanities();

        $i = 0;
        foreach ($profanities as $word) {
            $profanity = new Profanity();
            $profanity->setWord($word);

            $em->persist($profanity);

            if (($i % 100) === 0) {
                $em->flush();
                $em->clear();
            }
            ++$i;
        }

        $em->flush();

        $output->writeln(sprintf('Populated %d words', $i));
    }
}