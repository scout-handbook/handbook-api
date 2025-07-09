<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or exit('Restricted access.');

class MissingArgumentException extends Exception
{
    protected const TYPE = 'MissingArgumentException';

    protected const STATUS = 400;

    public const GET = 'GET';

    public const POST = 'POST';

    public const FILE = 'FILE';

    public function __construct(string $type, string $name)
    {
        parent::__construct($type.' argument "'.$name.'" must be provided.');
    }
}
