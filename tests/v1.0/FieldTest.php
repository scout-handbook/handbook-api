<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Field;

#[CoversClass(Field::class)]
class FieldTest extends TestCase
{
    public function testCtor(): Field
    {
        $field = new Field('fname', 'fdesc', pack('H*', '2739a63aa2544a959508103b7c80bcdb'), pack('H*', '3739a63aa2544a959508103b7c80bcdb')); // phpcs:ignore Generic.Files.LineLength.TooLong
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Field', $field);
        return $field;
    }

    #[Depends("testCtor")]
    public function testJsonSerializeNoLessons(Field $field): void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"name":"fname","description":"fdesc","image":"2739a63a-a254-4a95-9508-103b7c80bcdb","icon":"3739a63a-a254-4a95-9508-103b7c80bcdb","lessons":[]}', // phpcs:ignore Generic.Files.LineLength.TooLong
            json_encode($field)
        );
    }

    public function testJsonSerializeLessons(): void
    {
        $field = new Field('fname', 'fdesc', pack('H*', '2739a63aa2544a959508103b7c80bcdb'), pack('H*', '3739a63aa2544a959508103b7c80bcdb')); // phpcs:ignore Generic.Files.LineLength.TooLong
        $field->addLesson(pack('H*', '1739a63ab2544a959508103b7c80bcdb'));
        $this->assertJsonStringEqualsJsonString(
            '{"name":"fname","description":"fdesc","image":"2739a63a-a254-4a95-9508-103b7c80bcdb","icon":"3739a63a-a254-4a95-9508-103b7c80bcdb","lessons":["1739a63a-b254-4a95-9508-103b7c80bcdb"]}', // phpcs:ignore Generic.Files.LineLength.TooLong
            json_encode($field)
        );
    }

    public function testCtorInvalidImage(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Field(
            'fname',
            'fdesc',
            pack('H*', '2739a63aa2544a959508103b7c80bcdbf'),
            pack('H*', '3739a63aa2544a959508103b7c80bcdbf')
        );
    }
}
