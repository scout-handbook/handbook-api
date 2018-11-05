<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/DeletedLesson.php');

class DeletedLessonTest extends \PHPUnit\Framework\TestCase
{
    /*
     * @covers HandbookAPI\DeletedLesson::__construct()
     */
    public function testCtor()
    {
        $deletedLesson = new \HandbookAPI\DeletedLesson(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'dlname');
        $this->assertInstanceOf('\HandbookAPI\DeletedLesson', $deletedLesson);
        return $deletedLesson;
    }

    /**
     * @covers HandbookAPI\DeletedLesson::jsonSerialize()
     * @depends testCtor
     */
    public function testJsonSerialize(\HandbookAPI\DeletedLesson $deletedLesson) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","name":"dlname"}',
            json_encode($deletedLesson)
        );
    }

    /**
     * @covers HandbookAPI\DeletedLesson::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalid() // TODO: Specialize exception type
    {
        new \HandbookAPI\DeletedLesson(pack('H*', '1739a63aa2544a959508103b7c80bcdbf'), 'dlname');
    }
}
