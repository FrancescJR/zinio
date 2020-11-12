<?php
declare(strict_types=1);

namespace Zinio\Cesc\Infrastructure\Console;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zinio\Cesc\Application\City\GetShortestPathService;
use Zinio\Cesc\Domain\City\Exception\InvalidValueException;

class GetShortestPathCommand extends Command
{
    public const FILENAME = 'cities.txt';
    private const FILENAME_ARGUMENT = 'filename';
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
        $this->addArgument(
            self::FILENAME_ARGUMENT,
            InputArgument::OPTIONAL,
            "the filename in case you want to use a different one",
            self::FILENAME
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cities = [];
        // get File
        $file = $input->getArgument(self::FILENAME_ARGUMENT);
        try {
            if ( ! file_exists($file)) {
                throw new Exception("File" . $file . "not found");
            }
            $handler = fopen($file, "r");

            if ( ! $handler) {
                throw new Exception("Could not open file");
            }

            while (($city = fgetcsv($handler, 1000, "\t")) !== false) {
                if (count($city) != 3) {
                    throw new Exception("Delimiter must be tab character. Each line should have 3 fields.");
                }
                if ( ! is_numeric($city[1]) or ! is_numeric($city[2])) {
                    throw new Exception("Latitude and longitude of the city must be numbers.");
                }
                $cities[] = $city;
            }
        } catch (Exception $e) {
            $output->writeln('ERROR: ' . $e->getMessage());

            return Command::FAILURE;
        }

        // execute service
        try {
            $cityPath = $this->getShortestPathService->execute($cities);
        } catch (InvalidValueException $e) {
            $output->writeln('ERROR: ' . $e->getMessage());

            return Command::FAILURE;
        }

        foreach ($cityPath as $cityPO) {
            $output->write($cityPO);
        }

        return Command::SUCCESS;
    }
}
