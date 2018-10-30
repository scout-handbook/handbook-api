<?php declare(strict_types=1);
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/v0.9/internal/exceptions/Exception.php');

class NotLockedException extends Exception
{
    const TYPE = 'NotLockedException';
    const STATUS = 412;

    public function __construct()
    {
        parent::__construct('This resource must be locked by a mutex for this operation.');
    }
}
