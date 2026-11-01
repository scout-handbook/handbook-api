<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or exit('Restricted access.');

use PHPMD\Attribute\SuppressWarnings;
use PHPMD\Rule\Controversial\CamelCaseParameterName;

class ConnectionException extends Exception
{
    protected const TYPE = 'ConnectionException';

    protected const STATUS = 500;

    #[SuppressWarnings(CamelCaseParameterName::class)]
    public function __construct($PDOexception)
    {
        parent::__construct(
            'Database connection request failed. '.
            'Error message: "'.$PDOexception->getMessage().'".'
        );
    }
}
