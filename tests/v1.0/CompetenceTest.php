<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Competence;

#[CoversClass(Competence::class)]
class CompetenceTest extends TestCase
{
    public function testCtor() : Competence
    {
        $competence = new Competence(42, 'cname', 'cdescription');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Competence', $competence);
        return $competence;
    }

    /**
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
