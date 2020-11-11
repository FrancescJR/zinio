<?php

#start Symfony App
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Zinio\Cesc\Kernel;

require __DIR__.'/vendor/autoload.php';

$kernel = new Kernel('dev', true);

$application = new Application($kernel);
$application->setAutoExit(false);

$input = new ArrayInput([
    'command' => 'cesc:solve',
]);


$output = new BufferedOutput();
try {
    $application->run($input, $output);
}catch(Exception $e) {
    echo $e->getMessage();
}

$content = $output->fetch();

echo $content;

