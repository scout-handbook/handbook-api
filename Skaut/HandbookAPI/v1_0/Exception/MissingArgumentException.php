<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class MissingArgumentException extends Exception
{
    const TYPE = 'MissingArgumentException';
    const STATUS = 400;

    const GET = "GET";
    const POST = "POST";
    const FILE = "FILE";

    public function __construct(string $type, string $name)
    {
        parent::__construct($type . ' argument "' . $name . '" must be provided.');
    }
}
