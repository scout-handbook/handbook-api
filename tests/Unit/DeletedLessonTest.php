<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Skaut\HandbookAPI\v1_0\DeletedLesson;

#[CoversClass(DeletedLesson::class)]
class DeletedLessonTest extends TestCase
{
    public function test_ctor()
    {
        $deletedLesson = new DeletedLesson('dlname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\DeletedLesson', $deletedLesson);

        return $deletedLesson;
    }

    #[Depends('test_ctor')]
    public function test_json_serialize(DeletedLesson $deletedLesson): void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"name":"dlname"}',
            json_encode($deletedLesson)
        );
    }
}
