<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

class LessonContainer implements \JsonSerializable
{
    protected $lessons;

    public function __construct()
    {
        $this->lessons = [];
    }

    public function addLesson(Lesson $lesson) : void
    {
        $this->lessons[] = $lesson;
    }

    public function getLessons() : array
    {
        return $this->lessons;
    }

    public function sortLessons() : void
    {
        usort($this->lessons, 'Skaut\HandbookAPI\v0_9\Lesson::compare');
    }

    public function jsonSerialize() : array
    {
        return ['lessons' => $this->lessons];
    }

    // Container comparison function used in usort. Assumes that both Containers have their lessons sorted low-to-high.
    public static function compare(LessonContainer $first, LessonContainer $second) : int
    {
        if (get_class($first) === 'Skaut\HandbookAPI\v0_9\LessonContainer') {
            if (get_class($second) === 'Skaut\HandbookAPI\v0_9\LessonContainer') {
                return 0;
            }
            return -1;
        }
        if (get_class($second) === 'Skaut\HandbookAPI\v0_9\LessonContainer') {
            return 1;
        }
        if (empty($first->lessons)) {
            if (empty($second->lessons)) {
                return 0;
            }
            return -1;
        }
        if (empty($second->lessons)) {
            return 1;
        }
        return Lesson::compare($first->lessons[0], $second->lessons[0]);
    }
}
