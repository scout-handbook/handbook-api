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
        $competence = new Competence(42, 'cname', 'cdescription');
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
            '{"number":42,"name":"cname","description":"cdescription"}',
            json_encode($competence)
        );
    }
}
