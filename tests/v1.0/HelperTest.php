<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Exception\NotFoundException;

#[CoversClass(Helper::class)]
class HelperTest extends TestCase
{
    public function testParseUuid() : void
    {
        $uuid = Helper::parseUuid('6f99ef12-4815-4f5e-9ede-40d14007a3d1', '');
        $this->assertInstanceOf('\Ramsey\Uuid\UuidInterface', $uuid);
        $this->assertSame('6f99ef12-4815-4f5e-9ede-40d14007a3d1', $uuid->toString());
    }

    public function testParseUuidInvalid() : void
    {
        $this->expectException(NotFoundException::class);
        $uuid = Helper::parseUuid('6f99ef12-4815-4f5e-9ede-40d14007a3d12', '');
    }

    public function testXssSanitize() : void
    {
        $this->assertSame(
            'abc&amp;abc&quot;abc&apos;abc&lt;abc&gt;abc+ěščřžýáíé',
            Helper::xssSanitize('abc&abc"abc\'abc<abc>abc+ěščřžýáíé')
        );
    }

    public function testUrlEscape() : void
    {
        $this->assertSame('acdeeilnorstuuyz', Helper::urlEscape('áčďéěíľňóřšťúůýž'));
        $this->assertSame('acdeeilnorstuuyz', Helper::urlEscape('ÁČĎÉĚÍĽŇÓŘŠŤÚŮÝŽ'));
        $this->assertSame('abc', Helper::urlEscape('AbC'));
        $this->assertSame('abc', Helper::urlEscape(" abc\t"));
        $this->assertSame('a-b-c', Helper::urlEscape('a b  c'));
        $this->assertSame('ab', Helper::urlEscape('a+b'));
        $this->assertSame('a-b', Helper::urlEscape('a_b'));
        $this->assertSame('a-b', Helper::urlEscape('a--b'));
    }
}
