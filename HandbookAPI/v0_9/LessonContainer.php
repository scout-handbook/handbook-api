<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

class LessonContainer
{
    public $lessons = array();

    public function __construct()
    {
    }
}
