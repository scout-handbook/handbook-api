<?php declare(strict_types=1);
namespace v0_9;

use PHPUnit\Framework\TestCase;

global $CONFIG;
require_once('v0.9/internal/Helper.php');

class HelperTest extends TestCase
{
    /**
     * @covers HandbookAPI\Helper::parseUuid()
     */
    public function testParseUuid() : void
    {
        $uuid = \HandbookAPI\Helper::parseUuid('6f99ef12-4815-4f5e-9ede-40d14007a3d1', '');
        $this->assertInstanceOf('\Ramsey\Uuid\Uuid', $uuid);
        $this->assertEquals('6f99ef12-4815-4f5e-9ede-40d14007a3d1', $uuid->toString());
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
        $this->assertEquals(
            'abc&amp;abc&quot;abc&apos;abc&lt;abc&gt;abc+ěščřžýáíé',
            \HandbookAPI\Helper::xssSanitize('abc&abc"abc\'abc<abc>abc+ěščřžýáíé')
        );
    }

    /**
     * @covers HandbookAPI\Helper::urlEscape()
     */
    public function testUrlEscape() : void
    {
        $this->assertEquals('acdeeilnorstuuyz', \HandbookAPI\Helper::urlEscape('áčďéěíľňóřšťúůýž'));
        $this->assertEquals('acdeeilnorstuuyz', \HandbookAPI\Helper::urlEscape('ÁČĎÉĚÍĽŇÓŘŠŤÚŮÝŽ'));
        $this->assertEquals('abc', \HandbookAPI\Helper::urlEscape('AbC'));
        $this->assertEquals('abc', \HandbookAPI\Helper::urlEscape(" abc\t"));
        $this->assertEquals('a-b-c', \HandbookAPI\Helper::urlEscape('a b  c'));
        $this->assertEquals('ab', \HandbookAPI\Helper::urlEscape('a+b'));
        $this->assertEquals('a-b', \HandbookAPI\Helper::urlEscape('a_b'));
        $this->assertEquals('a-b', \HandbookAPI\Helper::urlEscape('a--b'));
    }
}
