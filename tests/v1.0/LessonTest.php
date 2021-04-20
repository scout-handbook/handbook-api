<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Competence;
use Skaut\HandbookAPI\v1_0\Lesson;

/** @SuppressWarnings(PHPMD.TooManyPublicMethods) */
class LessonTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\Lesson::__construct()
     */
    public function testCtor() : Lesson
    {
        $lesson = new Lesson('lname', 123.4567);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Lesson', $lesson);
        return $lesson;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Lesson::jsonSerialize
     * @depends testCtor
     */
    public function testJsonSerializeNoCompetences(Lesson $lesson) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"name":"lname","version":123457,"competences":[]}',
            json_encode($lesson)
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Lesson::jsonSerialize
     */
    public function testJsonSerializeCompetences() : void
    {
        $lesson = new Lesson('lname', 123.4567);
        $lesson->addCompetence(pack('H*', '1739a63ab2544a959508103b7c80bcdb'));
        $this->assertJsonStringEqualsJsonString(
            '{"name":"lname","version":123457,"competences":["1739a63a-b254-4a95-9508-103b7c80bcdb"]}',
            json_encode($lesson)
        );
    }
}
