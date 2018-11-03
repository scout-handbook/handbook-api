<?php declare(strict_types=1);
namespace v0_9;

use PHPUnit\Framework\TestCase;

global $CONFIG;
require_once('v0.9/internal/Competence.php');

class CompetenceTest extends TestCase
{
    /**
     * @covers HandbookAPI\Competence::__construct()
     */
    public function testCtor() : \HandbookAPI\Competence
    {
        $competence = new \HandbookAPI\Competence(
            pack('H*', '1739a63aa2544a959508103b7c80bcdb'),
            42,
            'cname',
            'cdescription'
        );
        $this->assertInstanceOf('\HandbookAPI\Competence', $competence);
        return $competence;
    }

    /**
     * @covers HandbookAPI\Competence::jsonSerialize()
     * @depends testCtor
     */
    public function testJsonSerialize(\HandbookAPI\Competence $competence) : void
    {
        $this->assertEquals(
            '{"id":"1739a63a-a254-4a95-9508-103b7c80bcdb","number":42,"name":"cname","description":"cdescription"}',
            json_encode($competence)
        );
    }

    /**
     * @covers HandbookAPI\Competence::__construct()
     * @expectedException InvalidArgumentException
     */
    public function testCtorInvalid() // TODO: Specialize exception type
    {
        new \HandbookAPI\Competence(pack('H*', '2a0205609ddf4694b8ac8f846a195865f'), 42, 'cname', 'cdescription');
    }
}
