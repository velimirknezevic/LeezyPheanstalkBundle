<?php

namespace Leezy\PheanstalkBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Pheanstalk\Exception;

class DeleteJobCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('leezy:pheanstalk:delete-job')
            ->addArgument('job', InputArgument::REQUIRED, 'Jod id to delete.')
            ->setDescription('Delete the specified job if it exists.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jobId = $input->getArgument('job');
        
        $pheanstalk = $this->getContainer()->get("leezy.pheanstalk");
        try {
            $job = $pheanstalk->peek($jobId);
            $pheanstalk->delete($job);
            
            $output->writeln('Job <info>' . $jobId . '</info> deleted.');
        }
        catch (Exception $ex) {
            $output->writeln('Job not found');
        }
        
    }
}
