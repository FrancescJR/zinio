<?php
declare(strict_types=1);

namespace Infrastructure\Console;


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Zinio\Cesc\Infrastructure\Console\GetShortestPathCommand;

class GetShortestPathCommandTest extends KernelTestCase
{
    private $commandTester;


    public function setUp(): void
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find(GetShortestPathCommand::getDefaultName());
        $this->commandTester = new CommandTester($command);

    }

    public function testFileNotFound(): void
    {
        $this->commandTester->execute([
            'filename' => 'notexistfile.txt',
        ]);

        // the output of the command in the console
        $output =  $this->commandTester->getDisplay();
        $this->assertStringContainsString('ERROR', $output);
        $this->assertStringContainsString('not found', $output);
    }

    public function testBadFormatFile(): void
    {
        $file = 'wrongfile.txt';
        file_put_contents($file, "Barcelona\ttoo\tmane\tfields");
        $this->commandTester->execute([
            'filename' => $file,
        ]);
        unlink($file);

        // the output of the command in the console
        $output =  $this->commandTester->getDisplay();
        $this->assertStringContainsString('ERROR', $output);
        $this->assertStringContainsString('3 fields', $output);

        $file = 'wrongfile.txt';
        file_put_contents($file, "Barcelona\ttoo");
        $this->commandTester->execute([
            'filename' => $file,
        ]);
        unlink($file);

        // the output of the command in the console
        $output =  $this->commandTester->getDisplay();
        $this->assertStringContainsString('ERROR', $output);
        $this->assertStringContainsString('3 fields', $output);

        $file = 'wrongfile.txt';
        file_put_contents($file, "Barcelona\tnot\tnumeric");
        $this->commandTester->execute([
            'filename' => $file,
        ]);
        unlink($file);

        // the output of the command in the console
        $output =  $this->commandTester->getDisplay();
        $this->assertStringContainsString('ERROR', $output);
        $this->assertStringContainsString('numbers', $output);
    }

    public function testExecute():void
    {
        $file = 'regular.txt';
        file_put_contents($file, "Barcelona\t5\t6\nMadrid\t7\t8");
        $this->commandTester->execute([
            'filename' => $file,
        ]);
        unlink($file);

        // the output of the command in the console
        $output =  $this->commandTester->getDisplay();
        $this->assertStringContainsString('Barcelona', $output);
        $this->assertStringContainsString('Madrid', $output);
    }


}
