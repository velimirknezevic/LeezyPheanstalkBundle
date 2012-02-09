<?php

namespace Leezy\PheanstalkBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Pheanstalk\Pheanstalk;

class PutCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('leezy:pheanstalk:put')
            ->setDescription('Puts a job on the queue.')
            ->addArgument('tube', InputArgument::REQUIRED, 'Tube to put job.')
            ->addArgument('data', InputArgument::REQUIRED, 'The job data.')
            ->addArgument('priority', InputArgument::OPTIONAL, 'From 0 (most urgent) to 0xFFFFFFFF (least urgent).')
            ->addArgument('delay', InputArgument::OPTIONAL, 'Seconds to wait before job becomes ready.')
            ->addArgument('ttr', InputArgument::OPTIONAL, 'Time To Run: seconds a job can be reserved for.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tube = $input->getArgument('tube');
        $data = $input->getArgument('data');
        $priority = $input->getArgument('priority');
        $delay = $input->getArgument('delay');
        $ttr = $input->getArgument('ttr');
        
        $pheanstalk = $this->getContainer()->get("leezy.pheanstalk");
        
        if (null == $priority) {
            $priority = Pheanstalk::DEFAULT_PRIORITY;
        }
        
        if (null == $delay) {
            $delay = Pheanstalk::DEFAULT_DELAY;
        }
        
        if (null == $ttr) {
            $ttr = Pheanstalk::DEFAULT_TTR;
        }
        
        $pheanstalk->useTube ($tube);
        $jobId = $pheanstalk->put ($data, $priority, $delay, $ttr);
        
        $output->writeln('New job on tube <info>' . $tube . '</info> with id <info>' . $jobId . '</info>.');
    }
}
