<?php declare(strict_types=1);
namespace v0_9;

use PHPUnit\Framework\TestCase;

global $CONFIG;
require_once('v0.9/internal/User.php');

class UserTest extends TestCase
{
    /**
     * @covers HandbookAPI\User::__construct()
     */
    public function testCtor() : \HandbookAPI\User
    {
        $user = new \HandbookAPI\User(12345, 'uname', 'superuser');
        $this->assertInstanceOf('\HandbookAPI\User', $user);
        return $user;
    }

    /**
     * @covers HandbookAPI\User::jsonSerialize
     * @depends testCtor
     */
    public function testJsonSerializeNoGroups(\HandbookAPI\User $user) : void
    {
        $this->assertJsonStringEqualsJsonString(
            '{"id":12345,"name":"uname","role":"superuser","groups":[]}',
            json_encode($user)
        );
    }

    /**
     * @covers HandbookAPI\User::jsonSerialize
     */
    public function testJsonSerializeGroups() : void
    {
        $user = new \HandbookAPI\User(12345, 'uname', 'superuser');
        $user->groups[] = pack('H*', '1739a63aa2544a959508103b7c80bcdb');
        $this->assertJsonStringEqualsJsonString(
            '{"id":12345,"name":"uname","role":"superuser","groups":["1739a63a-a254-4a95-9508-103b7c80bcdb"]}',
            json_encode($user)
        );
    }
}
