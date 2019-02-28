<?php declare(strict_types=1);
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

class NotFoundException extends \Skaut\HandbookAPI\v0_9\Exception\Exception
{
    const TYPE = 'NotFoundException';
    const STATUS = 404;

    public function __construct(string $resourceName)
    {
        parent::__construct('No such ' . $resourceName . ' has been found.');
    }
}
