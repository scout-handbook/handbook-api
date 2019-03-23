<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Field;
use Skaut\HandbookAPI\v1_0\Lesson;
use Skaut\HandbookAPI\v1_0\LessonContainer;

class LessonContainerTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\LessonContainer::compare()
     */
    public function testCompareLessonContainerAndField() : void
    {
        $this->assertSame(
            -1,
            LessonContainer::compare(
                new LessonContainer(),
                new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'fname')
            )
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\LessonContainer::compare()
     */
    public function testCompareFieldAndLessonContainer() : void
    {
        $this->assertSame(
            1,
            LessonContainer::compare(
                new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'fname'),
                new LessonContainer()
            )
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\LessonContainer::compare()
     */
    public function testCompareLessonContainerAndLessonContainer() : void
    {
        $this->assertSame(
            0,
            LessonContainer::compare(
                new LessonContainer(),
                new LessonContainer()
            )
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\LessonContainer::compare()
     */
    public function testCompareLessonContainerBothEmpty() : void
    {
        $a = new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'aname');
        $b = new Field(pack('H*', '1739a63ab2544a959508103b7c80bcdb'), 'bname');
        $this->assertSame(0, LessonContainer::compare($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\LessonContainer::compare()
     */
    public function testCompareLessonContainerFirstEmpty() : void
    {
        $a = new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'aname');
        $b = new Field(pack('H*', '1739a63ab2544a959508103b7c80bcdb'), 'bname');
        $b->addLesson(new Lesson(pack('H*', '1739063ab2544a959508103b7c80bcdb'), 'blname', 123));
        $this->assertSame(-1, LessonContainer::compare($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\LessonContainer::compare()
     */
    public function testCompareLessonContainerSecondEmpty() : void
    {
        $a = new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'aname');
        $b = new Field(pack('H*', '1739a63ab2544a959508103b7c80bcdb'), 'bname');
        $a->addLesson(new Lesson(pack('H*', '1739063ab2544a959508103b7c80bcdb'), 'alname', 123));
        $this->assertSame(1, LessonContainer::compare($a, $b));
    }


    /**
     * @covers Skaut\HandbookAPI\v1_0\LessonContainer::compare()
     */
    public function testCompareLessonContainerNoneEmpty() : void
    {
        $a = new Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'aname');
        $b = new Field(pack('H*', '1739a63ab2544a959508103b7c80bcdb'), 'bname');
        $a->addLesson(new Lesson(pack('H*', '1739063ab2544a959508103b7c80bcdb'), 'alname', 123));
        $b->addLesson(new Lesson(pack('H*', '1735063ab2544a959508103b7c80bcdb'), 'blname', 456));
        $this->assertSame(0, LessonContainer::compare($a, $b));
    }
}
