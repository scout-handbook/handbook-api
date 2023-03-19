<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class LockedException extends Exception
{
    protected const TYPE = 'LockedException';
    protected const STATUS = 409;
    private $holder;

    public function __construct(string $holder)
    {
        parent::__construct('This resource is currently locked by a different user.');
        $this->holder = $holder;
    }

    public function handle(): array
    {
        return [
            'status' => self::STATUS,
            'type' => self::TYPE,
            'message' => $this->getMessage(),
            'holder' => $this->holder
        ];
    }
}
