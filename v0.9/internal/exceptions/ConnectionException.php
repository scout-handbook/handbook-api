<?php declare(strict_types=1);
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

class ConnectionException extends \Skaut\HandbookAPI\v0_9\Exception\Exception
{
    const TYPE = 'ConnectionException';
    const STATUS = 500;

    /** @SuppressWarnings(PHPMD.CamelCaseParameterName) */
    public function __construct($PDOexception)
    {
        parent::__construct(
            'Database connection request failed. ' .
            'Error message: "' . $PDOexception->getMessage() . '".'
        );
    }
}
