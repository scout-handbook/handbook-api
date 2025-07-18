<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or exit('Restricted access.');

class ConnectionException extends Exception
{
    protected const TYPE = 'ConnectionException';

    protected const STATUS = 500;

    /** @SuppressWarnings("PHPMD.CamelCaseParameterName") */
    public function __construct($PDOexception)
    {
        parent::__construct(
            'Database connection request failed. '.
            'Error message: "'.$PDOexception->getMessage().'".'
        );
    }
}
