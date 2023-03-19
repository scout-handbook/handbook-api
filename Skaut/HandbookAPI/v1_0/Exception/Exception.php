<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or die('Restricted access.');

/** @SuppressWarnings(PHPMD.NumberOfChildren) */
class Exception extends \Exception
{
    protected const TYPE = 'Exception';
    protected const STATUS = 500;

    public function handle(): array
    {
        return ['status' => static::STATUS, 'type' => static::TYPE, 'message' => $this->getMessage()];
    }
}
