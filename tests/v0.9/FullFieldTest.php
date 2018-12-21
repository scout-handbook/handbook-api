<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/FullField.php');

class FullFieldTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\FullField::__construct()
     */
    public function testCtor() : \HandbookAPI\FullField
    {
        $field = new \HandbookAPI\FullField(
            pack('H*', '1739a63aa2544a959508103b7c80bcdb'),
            'fnm',
            pack('H*', '2739a63aa2544a959508103b7c80bcdb')
        );
        $this->assertInstanceOf('\HandbookAPI\FUllField', $field);
        return $field;
    }

    /**
     * @covers HandbookAPI\FullField::jsonSerialize()
     * @depends testCtor
     */
    public function testJsonSerializeNoLessons(\HandbookAPI\FullField $field) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"fnm","image":"2739a63a-a254-4a95-9508-103b7c80bcdb"}',
            json_encode($field)
        );
    }

    /**
     * @covers HandbookAPI\FullField::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalidName() : void
    {
        new \HandbookAPI\FullField(
            pack('H*', '1739a63aa2544a959508103b7c80bcdbf'),
            'fname',
            pack('H*', '2739a63aa2544a959508103b7c80bcdb')
        );
    }

    /**
     * @covers HandbookAPI\FullField::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalidImage() : void
    {
        new \HandbookAPI\FullField(
            pack('H*', '1739a63aa2544a959508103b7c80bcdb'),
            'fname',
            pack('H*', '2739a63aa2544a959508103b7c80bcdbf')
        );
    }
}
