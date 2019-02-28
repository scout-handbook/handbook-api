<?php declare(strict_types=1);
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

class NotImplementedException extends \Skaut\HandbookAPI\v0_9\Exception\Exception
{
    const TYPE = 'NotImplementedException';
    const STATUS = 501;

    public function __construct()
    {
        parent::__construct('The requested feature has not been implemented.');
    }
}
