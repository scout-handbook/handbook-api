<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class ConnectionException extends Exception
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
