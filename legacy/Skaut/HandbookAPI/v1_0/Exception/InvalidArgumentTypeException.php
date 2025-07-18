<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or exit('Restricted access.');

class InvalidArgumentTypeException extends Exception
{
    protected const TYPE = 'InvalidArgumentTypeException';

    protected const STATUS = 415;

    public function __construct(string $name, array $types)
    {
        $typesString = '';
        $first = true;
        foreach ($types as $type) {
            if (! $first) {
                $typesString .= ', ';
            }
            $typesString .= $type;
            $first = false;
        }
        parent::__construct('Argument "'.$name.'" must be of type '.$typesString.'.');
    }
}
