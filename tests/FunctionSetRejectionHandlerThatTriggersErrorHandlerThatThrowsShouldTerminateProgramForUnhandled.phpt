--TEST--
The callback given to set_rejection_handler() may trigger a fatal error which in turn throws an exception which will terminate the program for unhandled rejection
--INI--
# suppress legacy PHPUnit 7 warning for Xdebug 3
xdebug.default_enable=
--FILE--
<?php

use function React\Promise\reject;
use function React\Promise\set_rejection_handler;

require __DIR__ . '/../vendor/autoload.php';

set_error_handler(function (int $_, string $errstr): void {
    throw new \OverflowException('This function should never throw');
});

set_rejection_handler(function (Throwable $e): void {
    trigger_error($e->getMessage(), E_USER_ERROR);
});

reject(new RuntimeException('foo'));

echo 'NEVER';

?>
--EXPECTF--
Fatal error: Uncaught OverflowException from unhandled promise rejection handler: This function should never throw in %s:%d
Stack trace:
%A
