<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class NotFoundException extends Exception
{
    protected const TYPE = 'NotFoundException';
    protected const STATUS = 404;

    public function __construct(string $resourceName)
    {
        parent::__construct('No such ' . $resourceName . ' has been found.');
    }
}
