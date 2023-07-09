<?php

namespace Cbr\Queuer\Command;

use Cbr\Queuer\CurrencyValidator;
use Cbr\Queuer\Queue\CollectRateTaskQueuer;
use Cbr\Sdk\Dto\CurrencyPair;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'queue')]
class QueueCommand extends Command
{
    public function __construct(
        protected CollectRateTaskQueuer $queuer,
        protected CurrencyValidator $currencyValidator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'days',
                InputArgument::REQUIRED,
                'Number of days to collect data for starting from today and back to the past'
            )->addArgument(
                'currency',
                InputArgument::REQUIRED,
                'Currency against base currency'
            )->addArgument(
                'base_currency',
                InputArgument::OPTIONAL,
                'Base currency (All against rub by default)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $daysCount = $input->getArgument('days');
        $currencyPair = new CurrencyPair(
            $input->getArgument('currency'),
            $input->getArgument('base_currency')
        );
        $this->validateCurrencyPair($currencyPair);

        $this->queuer->buildUpQueue($daysCount, $currencyPair);

        $output->writeln("$daysCount items added to queue for processing");

        return Command::SUCCESS;
    }

    /**
     * @NOTICE To follow SRP this might be extracted to separate validator like symfony validator and asserted by DTO properties.
     */
    protected function validateCurrencyPair(CurrencyPair $currencyPair): void
    {
        if ($currencyPair->baseCurrencyCode === $currencyPair->currencyCode) {
            throw new \Exception("Currencies can't be the same");
        }

        if (!$this->currencyValidator->isCurrencyCodeValid($currencyPair->currencyCode)) {
            throw new \Exception("$currencyPair->currencyCode is not valid");
        }

        if ($currencyPair->baseCurrencyCode && !$this->currencyValidator->isCurrencyCodeValid($currencyPair->baseCurrencyCode)) {
            throw new \Exception("$currencyPair->baseCurrencyCode is not valid");
        }
    }

}
