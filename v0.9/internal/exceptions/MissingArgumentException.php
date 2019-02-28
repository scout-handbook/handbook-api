<?php declare(strict_types=1);
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

class MissingArgumentException extends \Skaut\HandbookAPI\v0_9\Exception\Exception
{
    const TYPE = 'MissingArgumentException';
    const STATUS = 400;

    const GET = "GET";
    const POST = "POST";
    const FILE = "FILE";

    public function __construct(string $type, string $name)
    {
        parent::__construct($type . ' argument "' . $name . '" must be provided.');
    }
}
