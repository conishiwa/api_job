<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatter;

class ImportJobsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('job:import')
            ->setDescription('Import jobs')
            ->addArgument(
                'id',
                InputArgument::OPTIONAL,
                'Import one job by his id'
            )->addOption(
                'all',
                null,
                InputOption::VALUE_NONE,
                'Import all jobs from listing'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $usecase = $this->getContainer()->get('job_import_uc');
        $all = $input->getOption('all');
        $id = $input->getArgument('id');

        $output->setFormatter(new OutputFormatter(true));
        $output->writeln('<comment>Import started</comment>');

        if (!$all && !$id) {
            return $this->output($output);
        }
        if ($all){
            $ret =  $usecase->handle();
            return $this->output($output, $ret);
        }
        $ret =  $usecase->handle($id);
        return $this->output($output, $ret);

    }

    private function output(OutputInterface $output, $ret = []){
        if (!isset($ret['total'])){
            $ret['total'] = 0;
        }
        if (!isset($ret['ok'])){
            $ret['ok'] = 0;
        }
        if (!isset($ret['skipped'])){
            $ret['skipped'] = 0;
        }
        $ret['error'] =  $ret['total']-$ret['ok']-$ret['skipped'];


        $output->writeln('<info>Import finished</info>');
        $output->writeln('-----------------------------');
        if (!isset($ret['total']) || !$ret['total']){
            $output->writeln('<error>no job to import</error>');
            return;
        }

        $output->writeln('<comment>'.($ret['total'] ?:0  ).' job(s) to import</comment>');
        if ($ret['ok']) {
            $output->writeln('<info>' . ($ret['ok'] ?: 0) . ' job(s) ok</info>');
        }
        if ($ret['error']) {
            $output->writeln('<error>' . ($ret['error']) . ' job(s) failed</error>');
        }
        if ($ret['skipped']) {
            $output->writeln('<comment>' . ($ret['skipped']) . ' job(s) skipped (already imported)</comment>');
        }


        //$output->writeln($videosExportString);
    }
}