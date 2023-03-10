<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\FullField;

#[CoversClass(FullField::class)]
class FullFieldTest extends TestCase
{
    public function testCtor() : FullField
    {
        $field = new FullField(
            pack('H*', '1739a63aa2544a959508103b7c80bcdb'),
            'fname',
            'fdesc',
            pack('H*', '2739a63aa2544a959508103b7c80bcdb'),
            pack('H*', '3739a63aa2544a959508103b7c80bcdb')
        );
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\FullField', $field);
        return $field;
    }

    #[Depends("testCtor")]
    public function testJsonSerializeNoLessons(FullField $field) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"fname","description":"fdesc","image":"2739a63a-a254-4a95-9508-103b7c80bcdb","icon":"3739a63a-a254-4a95-9508-103b7c80bcdb"}', // phpcs:ignore Generic.Files.LineLength.TooLong
            json_encode($field)
        );
    }

    public function testCtorInvalidName() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        new FullField(
            pack('H*', '1739a63aa2544a959508103b7c80bcdbf'),
            'fname',
            'fdesc',
            pack('H*', '2739a63aa2544a959508103b7c80bcdb'),
            pack('H*', '3739a63aa2544a959508103b7c80bcdb')
        );
    }

    public function testCtorInvalidImage() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        new FullField(
            pack('H*', '1739a63aa2544a959508103b7c80bcdb'),
            'fname',
            'fdesc',
            pack('H*', '2739a63aa2544a959508103b7c80bcdbf'),
            pack('H*', '3739a63aa2544a959508103b7c80bcdb')
        );
    }

    public function testCtorInvalidIcon() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        new FullField(
            pack('H*', '1739a63aa2544a959508103b7c80bcdb'),
            'fname',
            'fdesc',
            pack('H*', '2739a63aa2544a959508103b7c80bcdb'),
            pack('H*', '3739a63aa2544a959508103b7c80bcdbf')
        );
    }
}
