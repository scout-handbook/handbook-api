<?php declare(strict_types=1);
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

class InvalidArgumentTypeException extends \Skaut\HandbookAPI\v0_9\Exception\Exception
{
    const TYPE = 'InvalidArgumentTypeException';
    const STATUS = 415;

    public function __construct(string $name, array $types)
    {
        $typesString = '';
        $first = true;
        foreach ($types as $type) {
            if (!$first) {
                $typesString .= ', ';
            }
            $typesString .= $type;
            $first = false;
        }
        parent::__construct('Argument "' . $name . '" must be of type ' . $typesString . '.');
    }
}
