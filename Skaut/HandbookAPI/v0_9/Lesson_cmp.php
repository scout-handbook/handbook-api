<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;

// Lesson comparison function used in usort. Assumes that both Lessons have their competences field sorted low-to-high.
function Lesson_cmp(Lesson $first, Lesson $second) : int
{
    if (empty($first->competences)) {
        if (empty($second->competences)) {
            return 0;
        }
        return -1;
    }
    if (empty($second->competences)) {
        return 1;
    }
    if ($first->lowestCompetence === $second->lowestCompetence) {
        return 0;
    }
    return ($first->lowestCompetence < $second->lowestCompetence) ? -1 : 1;
}
