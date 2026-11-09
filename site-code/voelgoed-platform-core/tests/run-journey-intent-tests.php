<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$implementation_files = [
    dirname(__DIR__) . '/includes/JourneyIntent/JourneyIntentCookie.php',
    dirname(__DIR__) . '/includes/JourneyIntent/JourneyIntentLock.php',
    dirname(__DIR__) . '/includes/JourneyIntent/JourneyIntentRepository.php',
    dirname(__DIR__) . '/includes/JourneyIntent/JourneyIntentService.php',
    dirname(__DIR__) . '/includes/JourneyIntent/JourneyIntentBricks.php',
];
foreach ($implementation_files as $implementation_file) {
    if (is_file($implementation_file)) {
        require_once $implementation_file;
    }
}

require_once __DIR__ . '/JourneyIntentTest.php';

$test = new JourneyIntentTest();
$passed = 0;
$failed = 0;
foreach (get_class_methods($test) as $method) {
    if (! str_starts_with($method, 'test_')) {
        continue;
    }

    try {
        $test->$method();
        ++$passed;
        echo 'PASS ' . $method . PHP_EOL;
    } catch (Throwable $error) {
        ++$failed;
        fwrite(STDERR, 'FAIL ' . $method . ': ' . $error->getMessage() . PHP_EOL);
    }
}

if ($failed > 0) {
    fwrite(STDERR, sprintf('%d of %d Journey Intent tests failed.' . PHP_EOL, $failed, $passed + $failed));
    exit(1);
}

echo sprintf('%d Journey Intent tests passed.' . PHP_EOL, $passed);
