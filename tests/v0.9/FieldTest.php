<?php declare(strict_types=1);
namespace v0_9;

use PHPUnit\Framework\TestCase;

global $CONFIG;
require_once('v0.9/internal/Field.php');
require_once('v0.9/internal/Lesson.php');

class FieldTest extends TestCase
{
    /**
     * @covers HandbookAPI\Field::__construct()
     */
    public function testCtor() : \HandbookAPI\Field
    {
        $field = new \HandbookAPI\Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'fname');
        $this->assertInstanceOf('\HandbookAPI\Field', $field);
        return $field;
    }

    /**
     * @covers HandbookAPI\Field::jsonSerialize()
     * @depends testCtor
     */
    public function testJsonSerializeNoLessons(\HandbookAPI\Field $field) : void
    {
        $this->assertEquals(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"fname","lessons":[]}',
            json_encode($field)
        );
    }

    /**
     * @covers HandbookAPI\Field::jsonSerialize()
     */
    public function testJsonSerializeLessons() : void
    {
        $field = new \HandbookAPI\Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'fname');
        $lesson = new \HandbookAPI\Lesson(pack('H*', '1739a63aa2544a959508103b7c80bcd0'), 'lname', 123);
        $field->lessons[] = $lesson;
        $this->assertEquals(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"fname","lessons":[' . json_encode($lesson) . ']}',
            json_encode($field)
        );
    }

    /**
     * @covers HandbookAPI\Field::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalid() : void // TODO: Specialize exception type
    {
        new \HandbookAPI\Field(pack('H*', '1739a63aa2544a959508103b7c80bcdbf'), 'fname', 123);
    }
}
