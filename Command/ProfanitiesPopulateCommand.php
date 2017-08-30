<?php

namespace Vangrg\ProfanityBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Vangrg\ProfanityBundle\Entity\Profanity;

/**
 * Class ProfanitiesPopulateCommand
 * @package Vangrg\ProfanityBundle\Command
 */
class ProfanitiesPopulateCommand extends ContainerAwareCommand
{
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
        $doctrine = $this->getContainer()->get('doctrine');

        $connectionName = $input->getOption('connection');
        $em = (empty($connectionName) === true)
            ? $doctrine->getManagerForClass('Vangrg\ProfanityBundle\Entity\Profanity')
            : $doctrine->getManager($connectionName);

        $profanities = $this->getContainer()->get('vangrg_profanity.storage.default')->getProfanities();

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

        $output->writeln('Success.');
    }
}