<?php
declare(strict_types=1);

namespace Zinio\Cesc\Infrastructure\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zinio\Cesc\Application\City\GetShortestPathService;
use Zinio\Cesc\Domain\City\Exception\InvalidValueException;

class GetShortestPathCommand extends Command
{
    protected static $defaultName = 'cesc:solve';

    private $getShortestPathService;

    public function __construct(
        GetShortestPathService $getShortestPathService
    ) {
        $this->getShortestPathService = $getShortestPathService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Challenge endpoint.');
        $this->setHelp('Solves the challenge.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void
     * @throws InvalidValueException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // get File
        $cities = [];

        // validate File

        // execute service
        try {
            $cityPath = $this->getShortestPathService->execute($cities);
        } catch (InvalidValueException $e) {
            $output->writeln('ERROR: ' . $e->getMessage());
            return 1;
        }

        foreach ($cityPath as $cityPO) {
            $output->write($cityPO);
        }

        return 0;
    }
}
