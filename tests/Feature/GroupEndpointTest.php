<?php

declare(strict_types=1);

namespace Tests\Feature;

use Skaut\HandbookAPI\v1_0\Database;
use Tests\LegacyEndpointTestCase;

/** @SuppressWarnings("PHPMD.TooManyPublicMethods") */
final class GroupEndpointTest extends LegacyEndpointTestCase
{
    public function test_empty_list(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200, 'response' => []]);
    }

    public function test_add_group(): void
    {
        $response = $this->post('v1.0/group', ['name' => 'Testovací skupina'], [], 'administrator');

        $response->assertStatus(201);
        $response->assertJson(['status' => 201]);
    }

    public function test_list_groups(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');

        $response->assertStatus(200);
        $response->assertJson(['status' => 200]);
        $response->assertJsonFragment(['name' => 'Testovací skupina']);
    }

    public function test_list_groups_without_authentication(): void
    {
        $response = $this->get('v1.0/group');

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'message' => 'Authentication failed.',
                'status' => 403,
                'type' => 'AuthenticationException',
            ],
        );
    }

    public function test_add_group_without_auth(): void
    {
        $response = $this->post('v1.0/group', ['name' => 'Nepovolená skupina'], []);

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'message' => 'Authentication failed.',
                'status' => 403,
                'type' => 'AuthenticationException',
            ],
        );
    }

    public function test_add_group_without_name(): void
    {
        $response = $this->post('v1.0/group', [], [], 'administrator');

        $response->assertStatus(400);
        $response->assertExactJson(
            [
                'message' => 'POST argument "name" must be provided.',
                'status' => 400,
                'type' => 'MissingArgumentException',
            ],
        );
    }

    public function test_update_group(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');
        $response->assertStatus(200);

        $groupId = key($response['response']);

        $response = $this->put(
            'v1.0/group/'.$groupId,
            ['name' => 'Změněná skupina'],
            [],
            'administrator',
        );

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200]);

        $response = $this->get('v1.0/group', [], 'editor');
        $response->assertJsonFragment(['name' => 'Změněná skupina']);
    }

    public function test_update_group_without_name(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');
        $response->assertStatus(200);

        $groupId = key($response['response']);

        $response = $this->put(
            'v1.0/group/'.$groupId,
            [],
            [],
            'administrator',
        );

        $response->assertStatus(400);
        $response->assertExactJson(
            [
                'message' => 'POST argument "name" must be provided.',
                'status' => 400,
                'type' => 'MissingArgumentException',
            ],
        );
    }

    public function test_update_group_without_auth(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');
        $response->assertStatus(200);

        $groupId = key($response['response']);

        $response = $this->put('v1.0/group/'.$groupId, ['name' => 'Neoprávněná změna'], []);

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'message' => 'Authentication failed.',
                'status' => 403,
                'type' => 'AuthenticationException',
            ],
        );
    }

    public function test_update_nonexistent_group(): void
    {
        $response = $this->put(
            'v1.0/group/nonexistent',
            ['name' => 'Nová skupina'],
            [],
            'administrator',
        );

        $response->assertStatus(404);
        $response->assertExactJson(
            [
                'message' => 'No such group has been found.',
                'status' => 404,
                'type' => 'NotFoundException',
            ],
        );
    }

    public function test_delete_group_without_auth(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');
        $groupId = key($response['response']);

        $response = $this->delete('v1.0/group/'.$groupId, [], []);

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'message' => 'Authentication failed.',
                'status' => 403,
                'type' => 'AuthenticationException',
            ],
        );
    }

    public function test_delete_group(): void
    {
        $response = $this->get('v1.0/group', [], 'editor');
        $groupId = key($response['response']);

        $response = $this->delete('v1.0/group/'.$groupId, [], [], 'administrator');
        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200]);

        $response = $this->get('v1.0/group');
        $response->assertJsonMissing(['name' => 'Změněná skupina']);
    }

    public function test_delete_nonexistent_group(): void
    {
        $response = $this->delete('v1.0/group/nonexistent', [], [], 'administrator');

        $response->assertStatus(404);
        $response->assertExactJson(
            [
                'message' => 'No such group has been found.',
                'status' => 404,
                'type' => 'NotFoundException',
            ],
        );
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        $db = new Database;
        $db->prepare(<<<'SQL'
DROP TABLE `groups`;
SQL);
        $db->execute();
        $db->prepare(<<<'SQL'
DROP TABLE `users_in_groups`;
SQL);
        $db->execute();
        $db->prepare(<<<'SQL'
DROP TABLE `groups_for_lessons`;
SQL);
        $db->execute();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $db = new Database;
        $db->prepare(<<<'SQL'
CREATE TABLE IF NOT EXISTS `groups` (
  `id` binary(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);
SQL);
        $db->execute();
        $db->prepare(<<<'SQL'
CREATE TABLE IF NOT EXISTS `users_in_groups` (
  `user_id` int UNSIGNED NOT NULL,
  `group_id` binary(16) NOT NULL
);
SQL);
        $db->execute();
        $db->prepare(<<<'SQL'
CREATE TABLE IF NOT EXISTS `groups_for_lessons` (
  `lesson_id` binary(16) NOT NULL,
  `group_id` binary(16) NOT NULL
);
SQL);
        $db->execute();
    }
}
