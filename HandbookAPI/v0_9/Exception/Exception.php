<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class Exception extends \Exception
{
    const TYPE = 'Exception';
    const STATUS = 500;

    public function handle() : array
    {
        return ['status' => static::STATUS, 'type' => static::TYPE, 'message' => $this->getMessage()];
    }
}
