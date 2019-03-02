<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use Skaut\HandbookAPI\v0_9\FullField;

class FullFieldTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\FullField::__construct()
     */
    public function testCtor() : FullField
    {
        $field = new FullField(
            pack('H*', '1739a63aa2544a959508103b7c80bcdb'),
            'fname',
            'fdesc',
            pack('H*', '2739a63aa2544a959508103b7c80bcdb')
        );
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\FullField', $field);
        return $field;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\FullField::jsonSerialize()
     * @depends testCtor
     */
    public function testJsonSerializeNoLessons(FullField $field) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"fname","description":"fdesc","image":"2739a63a-a254-4a95-9508-103b7c80bcdb"}', // phpcs:ignore Generic.Files.LineLength.TooLong
            json_encode($field)
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\FullField::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalidName() : void
    {
        new FullField(
            pack('H*', '1739a63aa2544a959508103b7c80bcdbf'),
            'fname',
            'fdesc',
            pack('H*', '2739a63aa2544a959508103b7c80bcdb')
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\FullField::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalidImage() : void
    {
        new FullField(
            pack('H*', '1739a63aa2544a959508103b7c80bcdb'),
            'fname',
            'fdesc',
            pack('H*', '2739a63aa2544a959508103b7c80bcdbf')
        );
    }
}
