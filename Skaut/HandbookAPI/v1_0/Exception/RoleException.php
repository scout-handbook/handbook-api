<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class RoleException extends Exception
{
    const TYPE = 'RoleException';
    const STATUS = 403;

    public function __construct()
    {
        parent::__construct('You don\'t have permission for this action.');
    }
}
