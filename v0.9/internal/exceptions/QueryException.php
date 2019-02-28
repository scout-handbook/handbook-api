<?php
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

class QueryException extends \Skaut\HandbookAPI\v0_9\Exception\Exception
{
    const TYPE = 'QueryException';
    const STATUS = 500;

    public function __construct(string $query, $db)
    {
        parent::__construct('Invalid query: "' . $query . '". Error message: "' . $db->errorInfo()[2] . '".');
    }
}
