<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\DeletedLesson;

class DeletedLessonTest extends TestCase
{
    /*
     * @covers Skaut\HandbookAPI\v0_9\DeletedLesson::__construct()
     */
    public function testCtor()
    {
        $deletedLesson = new DeletedLesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'dlname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\DeletedLesson', $deletedLesson);
        return $deletedLesson;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\DeletedLesson::jsonSerialize()
     * @depends testCtor
     */
    public function testJsonSerialize(DeletedLesson $deletedLesson) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"dlname"}',
            json_encode($deletedLesson)
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\DeletedLesson::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalid()
    {
        new DeletedLesson(pack('H*', '1739a63aa2544a959508103b7c80bcdbf'), 'dlname');
    }
}
