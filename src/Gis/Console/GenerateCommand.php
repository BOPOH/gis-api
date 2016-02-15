<?php

/**
 * @namespace
 */
namespace Gis\Console;

use \Knp\Command\Command;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends \Knp\Command\Command
{
    protected function configure()
    {
        $this
            ->setName('test')
            ->setDescription('Generate test data')
            ->addOption(
                'company-count',
                null,
                InputOption::VALUE_OPTIONAL,
                'Count of the companies',
                10
            )
            ->addOption(
                'rubric-count',
                null,
                InputOption::VALUE_OPTIONAL,
                'Count of the rubrics',
                10
            )
            ->addOption(
                'address-count',
                null,
                InputOption::VALUE_OPTIONAL,
                'Count of the addresses',
                10
            )
            ->addOption(
                'erase-data',
                null,
                InputOption::VALUE_NONE,
                'If sets erase old data'
            )
            ->addOption(
                'debug',
                null,
                InputOption::VALUE_NONE,
                'Tests command without real executiong'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $companyCount = $input->getOption('company-count');
        $addressCount = $input->getOption('address-count');
        $rubricCount = $input->getOption('rubric-count');
        $isDebug = $input->getOption('debug');
        $eraseOldData = $input->getOption('erase-data');

        $app = $this->getSilexApplication();
        $addressGenerator = new Generator\Address($app, $eraseOldData, $isDebug);
        $rubricGenerator = new Generator\Rubric($app, $eraseOldData, $isDebug);
        $companyGenerator = new Generator\Company($app, $eraseOldData, $isDebug);

        $addressesIds = $addressGenerator->generate($addressCount);
        $output->writeln(sprintf('Generated %s addresses', count($addressesIds)));

        $rubricsIds = $rubricGenerator->generate($rubricCount);
        $output->writeln(sprintf('Generated %s rubrics', count($rubricsIds)));

        $companiesIds = $companyGenerator->generate($addressesIds, $rubricsIds, $companyCount);
        $output->writeln(sprintf('Generated %s companies', count($companiesIds)));
    }
}
