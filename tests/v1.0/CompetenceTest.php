<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Competence;

class CompetenceTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\Competence::__construct()
     */
    public function testCtor() : Competence
    {
        $competence = new Competence(
            pack('H*', '1739a63aa2544a959508103b7c80bcdb'),
            42,
            'cname',
            'cdescription'
        );
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Competence', $competence);
        return $competence;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Competence::jsonSerialize()
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
     * @covers Skaut\HandbookAPI\v1_0\Competence::__construct()
     */
    public function testCtorInvalid() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Competence(pack('H*', '2a0205609ddf4694b8ac8f846a195865f'), 42, 'cname', 'cdescription');
    }
}
