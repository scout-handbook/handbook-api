<?php declare(strict_types=1);
namespace v0_9;

require_once('tests/v0.9/DatabaseTestCase.php');

global $CONFIG;
require_once('v0.9/internal/Role.php');

class RoleTest extends DatabaseTestCase
{
    /**
     * @covers HandbookAPI\Role::__construct()
     */
    public function testCtorSuperuser() : \HandbookAPI\Role
    {
        $superuser = new \HandbookAPI\Role('superuser');
        $this->assertInstanceOf('\HandbookAPI\Role', $superuser);
        return $superuser;
    }

    /**
     * @covers HandbookAPI\Role::__construct()
     */
    public function testCtorAdministrator() : \HandbookAPI\Role
    {
        $administrator = new \HandbookAPI\Role('administrator');
        $this->assertInstanceOf('\HandbookAPI\Role', $administrator);
        return $administrator;
    }

    /**
     * @covers HandbookAPI\Role::__construct()
     */
    public function testCtorEditor() : \HandbookAPI\Role
    {
        $editor = new \HandbookAPI\Role('editor');
        $this->assertInstanceOf('\HandbookAPI\Role', $editor);
        return $editor;
    }

    /**
     * @covers HandbookAPI\Role::__construct()
     */
    public function testCtorUser() : \HandbookAPI\Role
    {
        $user = new \HandbookAPI\Role('user');
        $this->assertInstanceOf('\HandbookAPI\Role', $user);
        return $user;
    }

    /**
     * @covers HandbookAPI\Role::__construct()
     */
    public function testCtorGuest() : \HandbookAPI\Role
    {
        $guest = new \HandbookAPI\Role('guest');
        $this->assertInstanceOf('\HandbookAPI\Role', $guest);
        return $guest;
    }

    /**
     * @covers HandbookAPI\Role::__construct()
     */
    public function testCtorNothing() : \HandbookAPI\Role
    {
        $nothing = new \HandbookAPI\Role('');
        $this->assertInstanceOf('\HandbookAPI\Role', $nothing);
        return $nothing;
    }

    /**
     * @covers HandbookAPI\Role::__construct()
     */
    public function testCtorText() : \HandbookAPI\Role
    {
        $text = new \HandbookAPI\Role('text');
        $this->assertInstanceOf('\HandbookAPI\Role', $text);
        return $text;
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorSuperuser
     */
    public function testToStringSuperuser($role) : void
    {
        $this->assertEquals('superuser', $role);
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorAdministrator
     */
    public function testToStringAdministrator($role) : void
    {
        $this->assertEquals('administrator', $role);
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorEditor
     */
    public function testToStringEditor($role) : void
    {
        $this->assertEquals('editor', $role);
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorUser
     */
    public function testToStringUser($role) : void
    {
        $this->assertEquals('user', $role);
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorGuest
     */
    public function testToStringGuest($role) : void
    {
        $this->assertEquals('guest', $role);
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorNothing
     */
    public function testToStringNothing($role) : void
    {
        $this->assertEquals('guest', $role);
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorText
     */
    public function testToStringText($role) : void
    {
        $this->assertEquals('guest', $role);
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorSuperuser
     */
    public function testJsonSerializeSuperuser($role) : void
    {
        $this->assertEquals('"superuser"', json_encode($role));
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorAdministrator
     */
    public function testJsonSerializeAdministrator($role) : void
    {
        $this->assertEquals('"administrator"', json_encode($role));
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorEditor
     */
    public function testJsonSerializeEditor($role) : void
    {
        $this->assertEquals('"editor"', json_encode($role));
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorUser
     */
    public function testJsonSerializeUser($role) : void
    {
        $this->assertEquals('"user"', json_encode($role));
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorGuest
     */
    public function testJsonSerializeGuest($role) : void
    {
        $this->assertEquals('"guest"', json_encode($role));
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorNothing
     */
    public function testJsonSerializeNothing($role) : void
    {
        $this->assertEquals('"guest"', json_encode($role));
    }

    /**
     * @covers HandbookAPI\Role::__toString()
     * @depends testCtorText
     */
    public function testJsonSerializeText($role) : void
    {
        $this->assertEquals('"guest"', json_encode($role));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorSuperuser
     */
    public function testRoleCompareSuperuserAndSuperuser(\HandbookAPI\Role $a) : void
    {
        $this->assertEquals(0, \HandbookAPI\Role_cmp($a, new \HandbookAPI\Role('superuser')));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorSuperuser
     * @depends testCtorAdministrator
     */
    public function testRoleCompareSuperuserAndAdministrator(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorSuperuser
     * @depends testCtorEditor
     */
    public function testRoleCompareSuperuserAndEditor(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorSuperuser
     * @depends testCtorUser
     */
    public function testRoleCompareSuperuserAndUser(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorSuperuser
     * @depends testCtorGuest
     */
    public function testRoleCompareSuperuserAndEditorGuest(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorAdministrator
     * @depends testCtorSuperuser
     */
    public function testRoleCompareAdministratorAndSuperuser(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(-1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorAdministrator
     */
    public function testRoleCompareAdministratorAndAdministrator(\HandbookAPI\Role $a) : void
    {
        $this->assertEquals(0, \HandbookAPI\Role_cmp($a, new \HandbookAPI\Role('administrator')));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorAdministrator
     * @depends testCtorEditor
     */
    public function testRoleCompareAdministratorAndEditor(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorAdministrator
     * @depends testCtorUser
     */
    public function testRoleCompareAdministratorAndUser(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorAdministrator
     * @depends testCtorGuest
     */
    public function testRoleCompareAdministratorAndGuest(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorEditor
     * @depends testCtorSuperuser
     */
    public function testRoleCompareEditorAndSuperuser(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(-1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorEditor
     * @depends testCtorAdministrator
     */
    public function testRoleCompareEditorAndAdministrator(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(-1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorEditor
     */
    public function testRoleCompareEditorAndEditor(\HandbookAPI\Role $a) : void
    {
        $this->assertEquals(0, \HandbookAPI\Role_cmp($a, new \HandbookAPI\Role('editor')));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorEditor
     * @depends testCtorUser
     */
    public function testRoleCompareEditorAndUser(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorEditor
     * @depends testCtorGuest
     */
    public function testRoleCompareEditorAndGuest(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorUser
     * @depends testCtorSuperuser
     */
    public function testRoleCompareUserAndSuperuser(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(-1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorUser
     * @depends testCtorAdministrator
     */
    public function testRoleCompareUserAndAdministrator(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(-1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorUser
     * @depends testCtorEditor
     */
    public function testRoleCompareUserAndEditor(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(-1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorUser
     */
    public function testRoleCompareUserAndUser(\HandbookAPI\Role $a) : void
    {
        $this->assertEquals(0, \HandbookAPI\Role_cmp($a, new \HandbookAPI\Role('user')));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorUser
     * @depends testCtorGuest
     */
    public function testRoleCompareUserAndGuest(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorGuest
     * @depends testCtorSuperuser
     */
    public function testRoleCompareGuestAndSuperuser(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(-1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorGuest
     * @depends testCtorAdministrator
     */
    public function testRoleCompareGuestAndAdministrator(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(-1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorGuest
     * @depends testCtorEditor
     */
    public function testRoleCompareGuestAndEditor(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(-1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorGuest
     * @depends testCtorUser
     */
    public function testRoleCompareGuestAndUser(\HandbookAPI\Role $a, \HandbookAPI\Role $b) : void
    {
        $this->assertEquals(-1, \HandbookAPI\Role_cmp($a, $b));
    }

    /**
     * @covers HandbookAPI\Role_cmp()
     * @depends testCtorGuest
     */
    public function testRoleCompareGuestAndGuest(\HandbookAPI\Role $a) : void
    {
        $this->assertEquals(0, \HandbookAPI\Role_cmp($a, new \HandbookAPI\Role('guest')));
    }
}
