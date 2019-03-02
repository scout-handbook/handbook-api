<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

use Skaut\HandbookAPI\v0_9\Helper;

class HelperTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Helper::parseUuid()
     */
    public function testParseUuid() : void
    {
        $uuid = Helper::parseUuid('6f99ef12-4815-4f5e-9ede-40d14007a3d1', '');
        $this->assertInstanceOf('Uuid', $uuid);
        $this->assertSame('6f99ef12-4815-4f5e-9ede-40d14007a3d1', $uuid->toString());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Helper::parseUuid()
     * @expectedException Skaut\HandbookAPI\v0_9\Exception\NotFoundException
     */
    public function testParseUuidInvalid() : void
    {
        $uuid = Helper::parseUuid('6f99ef12-4815-4f5e-9ede-40d14007a3d12', '');
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Helper::xssSanitize()
     */
    public function testXssSanitize() : void
    {
        $this->assertSame(
            'abc&amp;abc&quot;abc&apos;abc&lt;abc&gt;abc+ěščřžýáíé',
            Helper::xssSanitize('abc&abc"abc\'abc<abc>abc+ěščřžýáíé')
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Helper::urlEscape()
     */
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
