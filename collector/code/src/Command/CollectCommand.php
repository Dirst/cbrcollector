<?php

namespace Cbr\Collector\Command;

use Cbr\Collector\QueueConsumer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'collect')]
class CollectCommand extends Command
{
    public function __construct(protected QueueConsumer $consumer)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->consumer->consume();

        return Command::SUCCESS;
    }

}
