<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\Field;
use Skaut\HandbookAPI\v0_9\Lesson;

#[CoversClass(Field::class)]
class FieldTest extends TestCase
{
    public function testCtor() : Field
    {
        $field = new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'fname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Field', $field);
        return $field;
    }

    #[Depends("testCtor")]
    public function testJsonSerializeNoLessons(Field $field) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"fname","lessons":[]}',
            json_encode($field)
        );
    }

    public function testJsonSerializeLessons() : void
    {
        $field = new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'fname');
        $lesson = new Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcd0'), 'lname', 123);
        $field->addLesson($lesson);
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"fname","lessons":[' . json_encode($lesson) . ']}',
            json_encode($field)
        );
    }

    public function testCtorInvalid() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdbf'), 'fname', 123);
    }
}
