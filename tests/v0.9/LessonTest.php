<?php declare(strict_types=1);
namespace v0_9;

use PHPUnit\Framework\TestCase;

global $CONFIG;
require_once('v0.9/internal/Lesson.php');
require_once('v0.9/internal/Competence.php');

class LessonTest extends TestCase
{
    /**
     * @covers HandbookAPI\Lesson::__construct()
     */
    public function testCtor() : \HandbookAPI\Lesson
    {
        $lesson = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $this->assertInstanceOf('\HandbookAPI\Lesson', $lesson);
        return $lesson;
    }

    /**
     * @covers HandbookAPI\Lesson::jsonSerialize
     * @depends testCtor
     */
    public function testJsonSerializeNoLessons(\HandbookAPI\Lesson $lesson) : void
    {
        $this->assertEquals(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"lname","version":123457,"competences":[]}',
            json_encode($lesson)
        );
    }

    /**
     * @covers HandbookAPI\Lesson::jsonSerialize
     */
    public function testJsonSerializeLessons() : void
    {
        $lesson = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $lesson->competences[] = pack('H*', '1739a63ab2544a959508103b7c80bcdb');
        $this->assertEquals(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"lname","version":123457,' .
                '"competences":["1739a63a-b254-4a95-9508-103b7c80bcdb"]}',
            json_encode($lesson)
        );
    }

    /**
     * @covers HandbookAPI\Lesson::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalid() : void // TODO: Specialize exception type
    {
        new HandbookAPI\Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdbf'), 'lname', 123.4567);
    }

    /**
     * @covers HandbookAPI\Lesson_cmp
     */
    public function testCompareLessonBothEmpty() : void
    {
        $a = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $this->assertEquals(0, \HandbookAPI\Lesson_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Lesson_cmp
     */
    public function testCompareLessonFirstEmpty() : void
    {
        $a = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $b->competences[] = pack('H*', '1739a63ab2544a959508103b7080bcdb');
        $this->assertEquals(-1, \HandbookAPI\Lesson_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Lesson_cmp
     */
    public function testCompareLessonSecondEmpty() : void
    {
        $a = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $a->competences[] = pack('H*', '1739a63ab2544a959508103b7080bcdb');
        $this->assertEquals(1, \HandbookAPI\Lesson_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Lesson_cmp
     */
    public function testCompareLessonFirstLower() : void
    {
        $a = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $a->competences[] = pack('H*', '1739a63ab2544a959508103b7080bcdb');
        $b->competences[] = pack('H*', '2739a63ab2544a959508103b7080bcdb');
        $a->lowestCompetence = 1;
        $b->lowestCompetence = 2;
        $this->assertEquals(-1, \HandbookAPI\Lesson_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Lesson_cmp
     */
    public function testCompareLessonSecondLower() : void
    {
        $a = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $a->competences[] = pack('H*', '1739a63ab2544a959508103b7080bcdb');
        $b->competences[] = pack('H*', '2739a63ab2544a959508103b7080bcdb');
        $a->lowestCompetence = 2;
        $b->lowestCompetence = 1;
        $this->assertEquals(1, \HandbookAPI\Lesson_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Lesson_cmp
     */
    public function testCompareLessonBothSame() : void
    {
        $a = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $a->competences[] = pack('H*', '1739a63ab2544a959508103b7080bcdb');
        $b->competences[] = pack('H*', '2739a63ab2544a959508103b7080bcdb');
        $a->lowestCompetence = 1;
        $b->lowestCompetence = 1;
        $this->assertEquals(0, \HandbookAPI\Lesson_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Lesson_cmp
     */
    public function testCompareLessonBothEmptyAndUndefined() : void
    {
        $a = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'lname', 123.4567);
        $b = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2542a959508103b7c80bcdb'), 'lname', 123.4567);
        $a->competences[] = pack('H*', '1739a63ab2544a959508103b7080bcdb');
        $b->competences[] = pack('H*', '2739a63ab2544a959508103b7080bcdb');
        $this->assertEquals(0, \HandbookAPI\Lesson_cmp($a, $b));
    }
}
