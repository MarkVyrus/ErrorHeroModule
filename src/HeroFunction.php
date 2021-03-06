<?php

declare(strict_types=1);

namespace ErrorHeroModule;

use Seld\JsonLint\JsonParser;
use Throwable;

use function get_class;
use function is_array;
use function strip_tags;

function detectMessageContentType(string $message): string
{
    return (new JsonParser())->lint($message) === null
        ? 'application/problem+json'
        : (strip_tags($message) === $message ? 'text/plain' : 'text/html');
}

function isExcludedException(array $excludeExceptionsConfig, Throwable $t): bool
{
    $exceptionOrErrorClass = get_class($t);

    $isExcluded = false;
    foreach ($excludeExceptionsConfig as $excludeException) {
        if ($exceptionOrErrorClass === $excludeException) {
            $isExcluded = true;
            break;
        }

        if (
            is_array($excludeException)
            && $excludeException[0] === $exceptionOrErrorClass
            && $excludeException[1] === $t->getMessage()
        ) {
            $isExcluded = true;
            break;
        }
    }

    return $isExcluded;
}
