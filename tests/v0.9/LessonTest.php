<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use function Skaut\HandbookAPI\v0_9\Lesson_cmp;
use Skaut\HandbookAPI\v0_9\Competence;
use Skaut\HandbookAPI\v0_9\Lesson;

class LessonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Lesson::__construct()
     */
    public function testCtor() : Lesson
    {
        $lesson = new Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Lesson', $lesson);
        return $lesson;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Lesson::jsonSerialize
     * @depends testCtor
     */
    public function testJsonSerializeNoLessons(Lesson $lesson) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"lname","version":123457,"competences":[]}',
            json_encode($lesson)
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Lesson::jsonSerialize
     */
    public function testJsonSerializeLessons() : void
    {
        $lesson = new Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $lesson->competences[] = pack('H*', '1739a63ab2544a959508103b7c80bcdb');
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"lname","version":123457,' .
                '"competences":["1739a63a-b254-4a95-9508-103b7c80bcdb"]}',
            json_encode($lesson)
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Lesson::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalid() : void
    {
        new Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdbf'), 'lname', 123.4567);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Lesson_cmp
     */
    public function testCompareLessonBothEmpty() : void
    {
        $a = new Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $this->assertSame(0, Lesson_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Lesson_cmp
     */
    public function testCompareLessonFirstEmpty() : void
    {
        $a = new Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $b->competences[] = pack('H*', '1739a63ab2544a959508103b7080bcdb');
        $this->assertSame(-1, Lesson_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Lesson_cmp
     */
    public function testCompareLessonSecondEmpty() : void
    {
        $a = new Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $a->competences[] = pack('H*', '1739a63ab2544a959508103b7080bcdb');
        $this->assertSame(1, Lesson_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Lesson_cmp
     */
    public function testCompareLessonFirstLower() : void
    {
        $a = new Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $a->competences[] = pack('H*', '1739a63ab2544a959508103b7080bcdb');
        $b->competences[] = pack('H*', '2739a63ab2544a959508103b7080bcdb');
        $a->lowestCompetence = 1;
        $b->lowestCompetence = 2;
        $this->assertSame(-1, Lesson_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Lesson_cmp
     */
    public function testCompareLessonSecondLower() : void
    {
        $a = new Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $a->competences[] = pack('H*', '1739a63ab2544a959508103b7080bcdb');
        $b->competences[] = pack('H*', '2739a63ab2544a959508103b7080bcdb');
        $a->lowestCompetence = 2;
        $b->lowestCompetence = 1;
        $this->assertSame(1, Lesson_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Lesson_cmp
     */
    public function testCompareLessonBothSame() : void
    {
        $a = new Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $a->competences[] = pack('H*', '1739a63ab2544a959508103b7080bcdb');
        $b->competences[] = pack('H*', '2739a63ab2544a959508103b7080bcdb');
        $a->lowestCompetence = 1;
        $b->lowestCompetence = 1;
        $this->assertSame(0, Lesson_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Lesson_cmp
     */
    public function testCompareLessonBothEmptyAndUndefined() : void
    {
        $a = new Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $a->competences[] = pack('H*', '1739a63ab2544a959508103b7080bcdb');
        $b->competences[] = pack('H*', '2739a63ab2544a959508103b7080bcdb');
        $this->assertSame(0, Lesson_cmp($a, $b));
    }
}
