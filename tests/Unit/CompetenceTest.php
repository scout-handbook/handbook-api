<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Skaut\HandbookAPI\v1_0\Competence;

#[CoversClass(Competence::class)]
class CompetenceTest extends TestCase
{
    public function test_ctor(): Competence
    {
        $competence = new Competence('42', 'cname', 'cdescription');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Competence', $competence);

        return $competence;
    }

    #[Depends('test_ctor')]
    public function test_json_serialize(Competence $competence): void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"number":"42","name":"cname","description":"cdescription"}',
            json_encode($competence)
        );
    }
}
