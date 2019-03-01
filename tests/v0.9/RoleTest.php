<?php declare(strict_types=1);
namespace v0_9;

require_once('tests/DatabaseTestCase.php');

global $CONFIG;

use function Skaut\HandbookAPI\v0_9\getRole;
use function Skaut\HandbookAPI\v0_9\Role_cmp;
use Skaut\HandbookAPI\v0_9\Role;

class RoleTest extends \TestUtils\DatabaseTestCase
{
    public function getDump() : string
    {
        return 'tests/v0.9/db.sql';
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__construct()
     */
    public function testCtorSuperuser() : Role
    {
        $superuser = new Role('superuser');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Role', $superuser);
        return $superuser;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__construct()
     */
    public function testCtorAdministrator() : Role
    {
        $administrator = new Role('administrator');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Role', $administrator);
        return $administrator;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__construct()
     */
    public function testCtorEditor() : Role
    {
        $editor = new Role('editor');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Role', $editor);
        return $editor;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__construct()
     */
    public function testCtorUser() : Role
    {
        $user = new Role('user');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Role', $user);
        return $user;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__construct()
     */
    public function testCtorGuest() : Role
    {
        $guest = new Role('guest');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Role', $guest);
        return $guest;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__construct()
     */
    public function testCtorNothing() : Role
    {
        $nothing = new Role('');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Role', $nothing);
        return $nothing;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__construct()
     */
    public function testCtorText() : Role
    {
        $text = new Role('text');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Role', $text);
        return $text;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__toString()
     * @depends testCtorSuperuser
     */
    public function testToStringSuperuser($role) : void
    {
        $this->assertEquals('superuser', $role);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__toString()
     * @depends testCtorAdministrator
     */
    public function testToStringAdministrator($role) : void
    {
        $this->assertEquals('administrator', $role);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__toString()
     * @depends testCtorEditor
     */
    public function testToStringEditor($role) : void
    {
        $this->assertEquals('editor', $role);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__toString()
     * @depends testCtorUser
     */
    public function testToStringUser($role) : void
    {
        $this->assertEquals('user', $role);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__toString()
     * @depends testCtorGuest
     */
    public function testToStringGuest($role) : void
    {
        $this->assertEquals('guest', $role);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__toString()
     * @depends testCtorNothing
     */
    public function testToStringNothing($role) : void
    {
        $this->assertEquals('guest', $role);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::__toString()
     * @depends testCtorText
     */
    public function testToStringText($role) : void
    {
        $this->assertEquals('guest', $role);
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::jsonSerialize()
     * @depends testCtorSuperuser
     */
    public function testJsonSerializeSuperuser($role) : void
    {
        $this->assertJsonStringEqualsJsonString('"superuser"', json_encode($role));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::jsonSerialize()
     * @depends testCtorAdministrator
     */
    public function testJsonSerializeAdministrator($role) : void
    {
        $this->assertJsonStringEqualsJsonString('"administrator"', json_encode($role));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::jsonSerialize()
     * @depends testCtorEditor
     */
    public function testJsonSerializeEditor($role) : void
    {
        $this->assertJsonStringEqualsJsonString('"editor"', json_encode($role));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::jsonSerialize()
     * @depends testCtorUser
     */
    public function testJsonSerializeUser($role) : void
    {
        $this->assertJsonStringEqualsJsonString('"user"', json_encode($role));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::jsonSerialize()
     * @depends testCtorGuest
     */
    public function testJsonSerializeGuest($role) : void
    {
        $this->assertJsonStringEqualsJsonString('"guest"', json_encode($role));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::jsonSerialize()
     * @depends testCtorNothing
     */
    public function testJsonSerializeNothing($role) : void
    {
        $this->assertJsonStringEqualsJsonString('"guest"', json_encode($role));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role::jsonSerialize()
     * @depends testCtorText
     */
    public function testJsonSerializeText($role) : void
    {
        $this->assertJsonStringEqualsJsonString('"guest"', json_encode($role));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorSuperuser
     */
    public function testRoleCompareSuperuserAndSuperuser(Role $a) : void
    {
        $this->assertSame(0, Role_cmp($a, new Role('superuser')));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorSuperuser
     * @depends testCtorAdministrator
     */
    public function testRoleCompareSuperuserAndAdministrator(Role $a, Role $b) : void
    {
        $this->assertSame(1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorSuperuser
     * @depends testCtorEditor
     */
    public function testRoleCompareSuperuserAndEditor(Role $a, Role $b) : void
    {
        $this->assertSame(1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorSuperuser
     * @depends testCtorUser
     */
    public function testRoleCompareSuperuserAndUser(Role $a, Role $b) : void
    {
        $this->assertSame(1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorSuperuser
     * @depends testCtorGuest
     */
    public function testRoleCompareSuperuserAndEditorGuest(Role $a, Role $b) : void
    {
        $this->assertSame(1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorAdministrator
     * @depends testCtorSuperuser
     */
    public function testRoleCompareAdministratorAndSuperuser(Role $a, Role $b) : void
    {
        $this->assertSame(-1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorAdministrator
     */
    public function testRoleCompareAdministratorAndAdministrator(Role $a) : void
    {
        $this->assertSame(0, Role_cmp($a, new Role('administrator')));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorAdministrator
     * @depends testCtorEditor
     */
    public function testRoleCompareAdministratorAndEditor(Role $a, Role $b) : void
    {
        $this->assertSame(1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorAdministrator
     * @depends testCtorUser
     */
    public function testRoleCompareAdministratorAndUser(Role $a, Role $b) : void
    {
        $this->assertSame(1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorAdministrator
     * @depends testCtorGuest
     */
    public function testRoleCompareAdministratorAndGuest(Role $a, Role $b) : void
    {
        $this->assertSame(1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorEditor
     * @depends testCtorSuperuser
     */
    public function testRoleCompareEditorAndSuperuser(Role $a, Role $b) : void
    {
        $this->assertSame(-1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorEditor
     * @depends testCtorAdministrator
     */
    public function testRoleCompareEditorAndAdministrator(Role $a, Role $b) : void
    {
        $this->assertSame(-1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorEditor
     */
    public function testRoleCompareEditorAndEditor(Role $a) : void
    {
        $this->assertSame(0, Role_cmp($a, new Role('editor')));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorEditor
     * @depends testCtorUser
     */
    public function testRoleCompareEditorAndUser(Role $a, Role $b) : void
    {
        $this->assertSame(1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorEditor
     * @depends testCtorGuest
     */
    public function testRoleCompareEditorAndGuest(Role $a, Role $b) : void
    {
        $this->assertSame(1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorUser
     * @depends testCtorSuperuser
     */
    public function testRoleCompareUserAndSuperuser(Role $a, Role $b) : void
    {
        $this->assertSame(-1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorUser
     * @depends testCtorAdministrator
     */
    public function testRoleCompareUserAndAdministrator(Role $a, Role $b) : void
    {
        $this->assertSame(-1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorUser
     * @depends testCtorEditor
     */
    public function testRoleCompareUserAndEditor(Role $a, Role $b) : void
    {
        $this->assertSame(-1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorUser
     */
    public function testRoleCompareUserAndUser(Role $a) : void
    {
        $this->assertSame(0, Role_cmp($a, new Role('user')));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorUser
     * @depends testCtorGuest
     */
    public function testRoleCompareUserAndGuest(Role $a, Role $b) : void
    {
        $this->assertSame(1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorGuest
     * @depends testCtorSuperuser
     */
    public function testRoleCompareGuestAndSuperuser(Role $a, Role $b) : void
    {
        $this->assertSame(-1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorGuest
     * @depends testCtorAdministrator
     */
    public function testRoleCompareGuestAndAdministrator(Role $a, Role $b) : void
    {
        $this->assertSame(-1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorGuest
     * @depends testCtorEditor
     */
    public function testRoleCompareGuestAndEditor(Role $a, Role $b) : void
    {
        $this->assertSame(-1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorGuest
     * @depends testCtorUser
     */
    public function testRoleCompareGuestAndUser(Role $a, Role $b) : void
    {
        $this->assertSame(-1, Role_cmp($a, $b));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Role_cmp()
     * @depends testCtorGuest
     */
    public function testRoleCompareGuestAndGuest(Role $a) : void
    {
        $this->assertSame(0, Role_cmp($a, new Role('guest')));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\getRole
     */
    public function testGetRoleSuperuser() : void
    {
        $role = getRole(125099);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Role', $role);
        $this->assertSame('superuser', $role->__toString());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\getRole
     */
    public function testGetRoleAdministrator() : void
    {
        $role = getRole(125098);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Role', $role);
        $this->assertSame('administrator', $role->__toString());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\getRole
     */
    public function testGetRoleEditor() : void
    {
        $role = getRole(125097);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Role', $role);
        $this->assertSame('editor', $role->__toString());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\getRole
     */
    public function testGetRoleUser() : void
    {
        $role = getRole(125096);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Role', $role);
        $this->assertSame('user', $role->__toString());
    }
}
