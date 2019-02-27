<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/Helper.php');

use Ramsey\Uuid\Uuid;

class HelperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\Helper::parseUuid()
     */
    public function testParseUuid() : void
    {
        $uuid = \HandbookAPI\Helper::parseUuid('6f99ef12-4815-4f5e-9ede-40d14007a3d1', '');
        $this->assertInstanceOf('Uuid', $uuid);
        $this->assertSame('6f99ef12-4815-4f5e-9ede-40d14007a3d1', $uuid->toString());
    }

    /**
     * @covers HandbookAPI\Helper::parseUuid()
     * @expectedException HandbookAPI\NotFoundException
     */
    public function testParseUuidInvalid() : void
    {
        $uuid = \HandbookAPI\Helper::parseUuid('6f99ef12-4815-4f5e-9ede-40d14007a3d12', '');
    }

    /**
     * @covers HandbookAPI\Helper::xssSanitize()
     */
    public function testXssSanitize() : void
    {
        $this->assertSame(
            'abc&amp;abc&quot;abc&apos;abc&lt;abc&gt;abc+ěščřžýáíé',
            \HandbookAPI\Helper::xssSanitize('abc&abc"abc\'abc<abc>abc+ěščřžýáíé')
        );
    }

    /**
     * @covers HandbookAPI\Helper::urlEscape()
     */
    public function testUrlEscape() : void
    {
        $this->assertSame('acdeeilnorstuuyz', \HandbookAPI\Helper::urlEscape('áčďéěíľňóřšťúůýž'));
        $this->assertSame('acdeeilnorstuuyz', \HandbookAPI\Helper::urlEscape('ÁČĎÉĚÍĽŇÓŘŠŤÚŮÝŽ'));
        $this->assertSame('abc', \HandbookAPI\Helper::urlEscape('AbC'));
        $this->assertSame('abc', \HandbookAPI\Helper::urlEscape(" abc\t"));
        $this->assertSame('a-b-c', \HandbookAPI\Helper::urlEscape('a b  c'));
        $this->assertSame('ab', \HandbookAPI\Helper::urlEscape('a+b'));
        $this->assertSame('a-b', \HandbookAPI\Helper::urlEscape('a_b'));
        $this->assertSame('a-b', \HandbookAPI\Helper::urlEscape('a--b'));
    }
}
