<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Lesson;

#[CoversClass(Lesson::class)]
class LessonTest extends TestCase
{
    public function testCtor(): Lesson
    {
        $lesson = new Lesson('lname', 123.4567);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Lesson', $lesson);
        return $lesson;
    }

    #[Depends("testCtor")]
    public function testJsonSerializeNoCompetences(Lesson $lesson): void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"name":"lname","version":123457,"competences":[]}',
            json_encode($lesson)
        );
    }

    public function testJsonSerializeCompetences(): void
    {
        $lesson = new Lesson('lname', 123.4567);
        $lesson->addCompetence(pack('H*', '1739a63ab2544a959508103b7c80bcdb'));
        $this->assertJsonStringEqualsJsonString(
            '{"name":"lname","version":123457,"competences":["1739a63a-b254-4a95-9508-103b7c80bcdb"]}',
            json_encode($lesson)
        );
    }
}
