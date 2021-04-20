<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class QueryException extends Exception
{
    const TYPE = 'QueryException';
    const STATUS = 500;

    public function __construct(string $query, $db)
    {
        parent::__construct('Invalid query: "' . $query . '". Error message: "' . $db->errorInfo()[2] . '".');
    }
}
