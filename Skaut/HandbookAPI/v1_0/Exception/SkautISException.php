<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class SkautISException extends Exception
{
    const TYPE = 'SkautISException';
    const STATUS = 403;

    public function __construct(\Skautis\Exception $e)
    {
        parent::__construct('SkautIS error: ' . $e->getMessage());
    }
}
