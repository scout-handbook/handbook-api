<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use Skaut\HandbookAPI\v0_9\Competence;

class CompetenceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Competence::__construct()
     */
    public function testCtor() : Competence
    {
        $competence = new Competence(
            pack('H*', '1739a63aa2544a959508103b7c80bcdb'),
            42,
            'cname',
            'cdescription'
        );
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Competence', $competence);
        return $competence;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Competence::jsonSerialize()
     * @depends testCtor
     */
    public function testJsonSerialize(Competence $competence) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","number":42,"name":"cname","description":"cdescription"}',
            json_encode($competence)
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Competence::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalid() : void
    {
        new Competence(pack('H*', '2a0205609ddf4694b8ac8f846a195865f'), 42, 'cname', 'cdescription');
    }
}
