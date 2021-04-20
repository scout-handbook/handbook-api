<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class ExecutionException extends Exception
{
    const TYPE = 'ExecutionException';
    const STATUS = 500;

    public function __construct(string $query, $statement)
    {
        $message = 'Query "' . $query . '" has failed.';
        $errorInfo = $statement->errorInfo();
        if (isset($errorInfo[2])) {
            $message .= ' Error message: "' . $errorInfo[2] . '".';
        }
        parent::__construct($message);
    }
}
