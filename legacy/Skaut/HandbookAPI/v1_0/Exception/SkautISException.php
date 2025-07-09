<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or exit('Restricted access.');

class SkautISException extends Exception
{
    protected const TYPE = 'SkautISException';

    protected const STATUS = 403;

    public function __construct(\Skautis\Exception $e)
    {
        parent::__construct('SkautIS error: '.$e->getMessage());
    }
}
