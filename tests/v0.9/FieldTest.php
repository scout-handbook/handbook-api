<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/Lesson.php');

use Skaut\HandbookAPI\v0_9\Field;

class FieldTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Field::__construct()
     */
    public function testCtor() : Field
    {
        $field = new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'fname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Field', $field);
        return $field;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Field::jsonSerialize()
     * @depends testCtor
     */
    public function testJsonSerializeNoLessons(Field $field) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"fname","lessons":[]}',
            json_encode($field)
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Field::jsonSerialize()
     */
    public function testJsonSerializeLessons() : void
    {
        $field = new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'fname');
        $lesson = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcd0'), 'lname', 123);
        $field->lessons[] = $lesson;
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"fname","lessons":[' . json_encode($lesson) . ']}',
            json_encode($field)
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Field::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalid() : void
    {
        new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdbf'), 'fname', 123);
    }
}
