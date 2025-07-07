<?php

namespace Tests\Feature;

use Skaut\HandbookAPI\v1_0\Database;
use Tests\LegacyEndpointTestCase;

/** @SuppressWarnings("PHPMD.TooManyPublicMethods") */
class GroupEndpointTest extends LegacyEndpointTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $SQL1 = <<<SQL
CREATE TABLE IF NOT EXISTS `groups` (
  `id` binary(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);
SQL;
        $SQL2 = <<<SQL
CREATE TABLE IF NOT EXISTS `users_in_groups` (
  `user_id` int UNSIGNED NOT NULL,
  `group_id` binary(16) NOT NULL
);
SQL;
        $SQL3 = <<<SQL
CREATE TABLE IF NOT EXISTS `groups_for_lessons` (
  `lesson_id` binary(16) NOT NULL,
  `group_id` binary(16) NOT NULL
);
SQL;
        $db = new Database();
        $db->prepare($SQL1);
        $db->execute();
        $db->prepare($SQL2);
        $db->execute();
        $db->prepare($SQL3);
        $db->execute();
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        $SQL1 = "DROP TABLE `groups`;";
        $SQL2 = "DROP TABLE `users_in_groups`;";
        $SQL3 = "DROP TABLE `groups_for_lessons`;";
        $db = new Database();
        $db->prepare($SQL1);
        $db->execute();
        $db->prepare($SQL2);
        $db->execute();
        $db->prepare($SQL3);
        $db->execute();
    }

    public function testEmptyList(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200, 'response' => []]);
    }

    public function testAddGroup(): void
    {
        $response = $this->post('v1.0/group', ['name' => 'Testovací skupina'], [], 'administrator');

        $response->assertStatus(201);
        $response->assertJson(['status' => 201]);
    }

    public function testListGroups(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');

        $response->assertStatus(200);
        $response->assertJson(['status' => 200]);
        $response->assertJsonFragment(['name' => 'Testovací skupina']);
    }

    public function testListGroupsWithoutAuthentication(): void
    {
        $response = $this->get('v1.0/group');

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'status' => 403,
                'type' => 'AuthenticationException',
                'message' => 'Authentication failed.'
            ]
        );
    }

    public function testAddGroupWithoutAuth(): void
    {
        $response = $this->post('v1.0/group', ['name' => 'Nepovolená skupina'], []);

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'status' => 403,
                'type' => 'AuthenticationException',
                'message' => 'Authentication failed.'
            ]
        );
    }

    public function testAddGroupWithoutName(): void
    {
        $response = $this->post('v1.0/group', [], [], 'administrator');

        $response->assertStatus(400);
        $response->assertExactJson(
            [
                'status' => 400,
                'type' => 'MissingArgumentException',
                'message' => 'POST argument "name" must be provided.'
            ]
        );
    }

    public function testUpdateGroup(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');
        $response->assertStatus(200);

        $groupId = key($response['response']);

        $response = $this->put(
            'v1.0/group/' . $groupId,
            ['name' => 'Změněná skupina'],
            [],
            'administrator'
        );

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200]);

        $response = $this->get('v1.0/group', [], 'editor');
        $response->assertJsonFragment(['name' => 'Změněná skupina']);
    }

    public function testUpdateGroupWithoutName(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');
        $response->assertStatus(200);

        $groupId = key($response['response']);

        $response = $this->put(
            'v1.0/group/' . $groupId,
            [],
            [],
            'administrator'
        );

        $response->assertStatus(400);
        $response->assertExactJson(
            [
                'status' => 400,
                'type' => 'MissingArgumentException',
                'message' => 'POST argument "name" must be provided.'
            ]
        );
    }

    public function testUpdateGroupWithoutAuth(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');
        $response->assertStatus(200);

        $groupId = key($response['response']);

        $response = $this->put('v1.0/group/' . $groupId, ['name' => 'Neoprávněná změna'], []);

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'status' => 403,
                'type' => 'AuthenticationException',
                'message' => 'Authentication failed.'
            ]
        );
    }

    public function testUpdateNonexistentGroup(): void
    {
        $response = $this->put(
            'v1.0/group/nonexistent',
            ['name' => 'Nová skupina'],
            [],
            'administrator'
        );

        $response->assertStatus(404);
        $response->assertExactJson(
            [
                'status' => 404,
                'type' => 'NotFoundException',
                'message' => 'No such group has been found.'
            ]
        );
    }

    public function testDeleteGroupWithoutAuth(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');
        $groupId = key($response['response']);

        $response = $this->delete('v1.0/group/' . $groupId, [], []);

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'status' => 403,
                'type' => 'AuthenticationException',
                'message' => 'Authentication failed.'
            ]
        );
    }

    public function testDeleteGroup(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');
        $groupId = key($response['response']);

        $response = $this->delete('v1.0/group/' . $groupId, [], [], 'administrator');
        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200]);

        $response = $this->get('v1.0/group');
        $response->assertJsonMissing(['name' => 'Změněná skupina']);
    }

    public function testDeleteNonexistentGroup(): void
    {
        $response = $this->delete('v1.0/group/nonexistent', [], [], 'administrator');

        $response->assertStatus(404);
        $response->assertExactJson(
            [
                'status' => 404,
                'type' => 'NotFoundException',
                'message' => 'No such group has been found.'
            ]
        );
    }
}
