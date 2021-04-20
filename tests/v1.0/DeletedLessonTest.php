<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\DeletedLesson;

class DeletedLessonTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\DeletedLesson::__construct()
     */
    public function testCtor()
    {
        $deletedLesson = new DeletedLesson('dlname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\DeletedLesson', $deletedLesson);
        return $deletedLesson;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\DeletedLesson::jsonSerialize()
     * @depends testCtor
     */
    public function testJsonSerialize(DeletedLesson $deletedLesson) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"name":"dlname"}',
            json_encode($deletedLesson)
        );
    }
}
