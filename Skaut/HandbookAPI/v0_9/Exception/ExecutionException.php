<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class ExecutionException extends Exception
{
    const TYPE = 'ExecutionException';
    const STATUS = 500;

    public function __construct(string $query, $statement)
    {
        parent::__construct('Query "' . $query . '" has failed. Error message: "' . $statement->errorInfo()[2] . '".');
    }
}
