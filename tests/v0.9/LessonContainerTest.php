<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use function Skaut\HandbookAPI\v0_9\LessonContainer_cmp;
use Skaut\HandbookAPI\v0_9\Field;
use Skaut\HandbookAPI\v0_9\Lesson;
use Skaut\HandbookAPI\v0_9\LessonContainer;

class LessonContainerTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\LessonContainer::__construct()
     */
    public function testCtor() : LessonContainer
    {
        $lessonContainer = new LessonContainer();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\LessonContainer', $lessonContainer);
        return $lessonContainer;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\LessonContainer_cmp
     * @depends testCtor
     */
    public function testCompareLessonContainerAndField(LessonContainer $lessonContainer) : void
    {
        $this->assertSame(
            -1,
            LessonContainer_cmp(
                $lessonContainer,
                new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'fname')
            )
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\LessonContainer_cmp
     * @depends testCtor
     */
    public function testCompareFieldAndLessonContainer(LessonContainer $lessonContainer) : void
    {
        $this->assertSame(
            1,
            LessonContainer_cmp(
                new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'fname'),
                $lessonContainer
            )
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\LessonContainer_cmp
     * @depends testCtor
     */
    public function testCompareLessonContainerAndLessonContainer(LessonContainer $lessonContainer) : void
    {
        $this->assertSame(
            0,
            LessonContainer_cmp(
                $lessonContainer,
                new LessonContainer()
            )
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\LessonContainer_cmp
     */
    public function testCompareLessonContainerBothEmpty() : void
    {
        $a = new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'aname');
        $b = new Field(pack('H*', '1739a63ab2544a959508103b7c80bcdb'), 'bname');
        $this->assertSame(0, LessonContainer_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\LessonContainer_cmp
     */
    public function testCompareLessonContainerFirstEmpty() : void
    {
        $a = new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'aname');
        $b = new Field(pack('H*', '1739a63ab2544a959508103b7c80bcdb'), 'bname');
        $b->lessons[] = new Lesson(pack('H*', '1739063ab2544a959508103b7c80bcdb'), 'blname', 123);
        $this->assertSame(-1, LessonContainer_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\LessonContainer_cmp
     */
    public function testCompareLessonContainerSecondEmpty() : void
    {
        $a = new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'aname');
        $b = new Field(pack('H*', '1739a63ab2544a959508103b7c80bcdb'), 'bname');
        $a->lessons[] = new Lesson(pack('H*', '1739063ab2544a959508103b7c80bcdb'), 'alname', 123);
        $this->assertSame(1, LessonContainer_cmp($a, $b));
    }


    /**
     * @covers Skaut\HandbookAPI\v0_9\LessonContainer_cmp
     */
    public function testCompareLessonContainerNoneEmpty() : void
    {
        $a = new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'aname');
        $b = new Field(pack('H*', '1739a63ab2544a959508103b7c80bcdb'), 'bname');
        $a->lessons[] = new Lesson(pack('H*', '1739063ab2544a959508103b7c80bcdb'), 'alname', 123);
        $b->lessons[] = new Lesson(pack('H*', '1735063ab2544a959508103b7c80bcdb'), 'blname', 456);
        $this->assertSame(0, LessonContainer_cmp($a, $b));
    }
}
