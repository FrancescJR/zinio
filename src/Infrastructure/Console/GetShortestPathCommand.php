<?php
declare(strict_types=1);

namespace Zinio\Cesc\Infrastructure\Console;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zinio\Cesc\Application\City\GetShortestPathService;
use Zinio\Cesc\Domain\City\Exception\InvalidValueException;

class GetShortestPathCommand extends Command
{
    public const FILENAME = 'cities.txt';
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
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cities = [];
        // get File
        try {
            if ( ! file_exists(self::FILENAME)) {
                throw new Exception("File" . self::FILENAME . "not found");
            }
            $handler = fopen(self::FILENAME, "r");

            if (!$handler) {
                throw new Exception("Could not open file");
            }

            while (($city = fgetcsv($handler, 1000, "\t")) !== FALSE) {
                if (count($city) != 3) {
                    throw new Exception("Delimiter must be tab character. Each line should have 3 fields.");
                }
                if (! is_numeric($city[1]) or ! is_numeric($city[2])) {
                    throw new Exception("Latitude and longitude of the city must be numbers.");
                }
                $cities[] = $city;
            }
        } catch (Exception $e) {
            $output->writeln('ERROR: ' . $e->getMessage());

            return 1;
        }

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
