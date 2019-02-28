<?php declare(strict_types=1);
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

class ExecutionException extends \Skaut\HandbookAPI\v0_9\Exception\Exception
{
    const TYPE = 'ExecutionException';
    const STATUS = 500;

    public function __construct(string $query, $statement)
    {
        parent::__construct('Query "' . $query . '" has failed. Error message: "' . $statement->errorInfo()[2] . '".');
    }
}
