<?php declare(strict_types=1);
namespace v0_9;

use PHPUnit\Framework\TestCase;

global $CONFIG;
require_once('v0.9/internal/LessonContainer.php');
require_once('v0.9/internal/Field.php');
require_once('v0.9/internal/Lesson.php');

class LessonContainerTest extends TestCase
{
    /**
     * @covers HandbookAPI\LessonContainer::__construct()
     */
    public function testCtor() : \HandbookAPI\LessonContainer
    {
        $lessonContainer = new \HandbookAPI\LessonContainer();
        $this->assertInstanceOf('\HandbookAPI\LessonContainer', $lessonContainer);
        return $lessonContainer;
    }

    /**
     * @covers HandbookAPI\LessonContainer_cmp
     * @depends testCtor
     */
    public function testCompareLessonContainerAndField(\HandbookAPI\LessonContainer $lessonContainer) : void
    {
        $this->assertSame(
            -1,
            \HandbookAPI\LessonContainer_cmp(
                $lessonContainer,
                new \HandbookAPI\Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'fname')
            )
        );
    }

    /**
     * @covers HandbookAPI\LessonContainer_cmp
     * @depends testCtor
     */
    public function testCompareFieldAndLessonContainer(\HandbookAPI\LessonContainer $lessonContainer) : void
    {
        $this->assertSame(
            1,
            \HandbookAPI\LessonContainer_cmp(
                new \HandbookAPI\Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'fname'),
                $lessonContainer
            )
        );
    }

    /**
     * @covers HandbookAPI\LessonContainer_cmp
     * @depends testCtor
     */
    public function testCompareLessonContainerAndLessonContainer(\HandbookAPI\LessonContainer $lessonContainer) : void
    {
        $this->assertSame(
            0,
            \HandbookAPI\LessonContainer_cmp(
                $lessonContainer,
                new \HandbookAPI\LessonContainer()
            )
        );
    }

    /**
     * @covers HandbookAPI\LessonContainer_cmp
     */
    public function testCompareLessonContainerBothEmpty() : void
    {
        $a = new \HandbookAPI\Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'aname');
        $b = new \HandbookAPI\Field(pack('H*', '1739a63ab2544a959508103b7c80bcdb'), 'bname');
        $this->assertSame(0, \HandbookAPI\LessonContainer_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\LessonContainer_cmp
     */
    public function testCompareLessonContainerFirstEmpty() : void
    {
        $a = new \HandbookAPI\Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'aname');
        $b = new \HandbookAPI\Field(pack('H*', '1739a63ab2544a959508103b7c80bcdb'), 'bname');
        $b->lessons[] = new \HandbookAPI\Lesson(pack('H*', '1739063ab2544a959508103b7c80bcdb'), 'blname', 123);
        $this->assertSame(-1, \HandbookAPI\LessonContainer_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\LessonContainer_cmp
     */
    public function testCompareLessonContainerSecondEmpty() : void
    {
        $a = new \HandbookAPI\Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'aname');
        $b = new \HandbookAPI\Field(pack('H*', '1739a63ab2544a959508103b7c80bcdb'), 'bname');
        $a->lessons[] = new \HandbookAPI\Lesson(pack('H*', '1739063ab2544a959508103b7c80bcdb'), 'alname', 123);
        $this->assertSame(1, \HandbookAPI\LessonContainer_cmp($a, $b));
    }


    /**
     * @covers HandbookAPI\LessonContainer_cmp
     */
    public function testCompareLessonContainerNoneEmpty() : void
    {
        $a = new \HandbookAPI\Field(pack('H*', '1739a63aa2544a959508103b7c80bcdb'), 'aname');
        $b = new \HandbookAPI\Field(pack('H*', '1739a63ab2544a959508103b7c80bcdb'), 'bname');
        $a->lessons[] = new \HandbookAPI\Lesson(pack('H*', '1739063ab2544a959508103b7c80bcdb'), 'alname', 123);
        $b->lessons[] = new \HandbookAPI\Lesson(pack('H*', '1735063ab2544a959508103b7c80bcdb'), 'blname', 456);
        $this->assertSame(0, \HandbookAPI\LessonContainer_cmp($a, $b));
    }
}
