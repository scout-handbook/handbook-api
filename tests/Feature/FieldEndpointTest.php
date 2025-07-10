<?php

declare(strict_types=1);

namespace Tests\Feature;

use Skaut\HandbookAPI\v1_0\Database;
use Tests\LegacyEndpointTestCase;

/** @SuppressWarnings("PHPMD.TooManyPublicMethods") */
final class FieldEndpointTest extends LegacyEndpointTestCase
{
    public function test_empty_list(): void
    {
        $response = $this->get('v1.0/field');

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200, 'response' => []]);
    }

    public function test_add_field(): void
    {
        $response = $this->post(
            'v1.0/field',
            [
                'description' => 'Popis',
                'icon' => '00000000-0000-0000-0000-000000000000',
                'image' => '00000000-0000-0000-0000-000000000000',
                'name' => 'Testovací oblast',
            ],
            [],
            'administrator',
        );

        $response->assertStatus(201);
        $response->assertJson(['status' => 201]);
    }

    public function test_list_fields(): void
    {
        $response = $this->get('v1.0/field');
        $response->assertStatus(200);
        $response->assertJson(['status' => 200]);
        $response->assertJsonFragment(['name' => 'Testovací oblast']);
    }

    public function test_add_field_without_auth(): void
    {
        $response = $this->post('v1.0/field', ['name' => 'Nepovolená oblast'], []);
        $response->assertStatus(403);
        $response->assertExactJson([
            'message' => 'Authentication failed.',
            'status' => 403,
            'type' => 'AuthenticationException',
        ]);
    }

    public function test_add_field_without_name(): void
    {
        $response = $this->post('v1.0/field', [], [], 'administrator');
        $response->assertStatus(400);
        $response->assertExactJson([
            'message' => 'POST argument "name" must be provided.',
            'status' => 400,
            'type' => 'MissingArgumentException',
        ]);
    }

    public function test_update_field(): void
    {
        $response = $this->get('v1.0/field', [], 'editor');
        $response->assertStatus(200);

        $fieldId = key($response['response']);

        $response = $this->put(
            'v1.0/field/'.$fieldId,
            [
                'description' => 'Změněný popis',
                'icon' => '00000000-0000-0000-0000-000000000001',
                'image' => '00000000-0000-0000-0000-000000000001',
                'name' => 'Změněná oblast',
            ],
            [],
            'administrator',
        );

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200]);

        $response = $this->get('v1.0/field', [], 'editor');
        $response->assertJsonFragment(
            [
                'description' => 'Změněný popis',
                'icon' => '00000000-0000-0000-0000-000000000001',
                'image' => '00000000-0000-0000-0000-000000000001',
                'name' => 'Změněná oblast',
            ],
        );
    }

    public function test_update_field_without_name(): void
    {
        $response = $this->get('v1.0/field', [], 'editor');
        $response->assertStatus(200);

        $fieldId = key($response['response']);

        $response = $this->put(
            'v1.0/field/'.$fieldId,
            [],
            [],
            'administrator',
        );

        $response->assertStatus(400);
        $response->assertExactJson([
            'message' => 'POST argument "name" must be provided.',
            'status' => 400,
            'type' => 'MissingArgumentException',
        ]);
    }

    public function test_update_field_without_auth(): void
    {
        $response = $this->get('v1.0/field', [], 'editor');
        $response->assertStatus(200);

        $fieldId = key($response['response']);

        $response = $this->put('v1.0/field/'.$fieldId, ['name' => 'Neoprávněná změna'], []);

        $response->assertStatus(403);
        $response->assertExactJson([
            'message' => 'Authentication failed.',
            'status' => 403,
            'type' => 'AuthenticationException',
        ]);
    }

    public function test_update_nonexistent_field(): void
    {
        $response = $this->put(
            'v1.0/field/nonexistent',
            ['name' => 'Nová oblast'],
            [],
            'administrator',
        );

        $response->assertStatus(404);
        $response->assertExactJson([
            'message' => 'No such field has been found.',
            'status' => 404,
            'type' => 'NotFoundException',
        ]);
    }

    public function test_delete_field_without_auth(): void
    {
        $response = $this->get('v1.0/field', [], 'editor');
        $fieldId = key($response['response']);

        $response = $this->delete('v1.0/field/'.$fieldId, [], []);

        $response->assertStatus(403);
        $response->assertExactJson([
            'message' => 'Authentication failed.',
            'status' => 403,
            'type' => 'AuthenticationException',
        ]);
    }

    public function test_delete_field(): void
    {
        $response = $this->get('v1.0/field', [], 'editor');
        $fieldId = key($response['response']);

        $response = $this->delete('v1.0/field/'.$fieldId, [], [], 'administrator');
        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200]);

        $response = $this->get('v1.0/field', [], 'editor');
        $response->assertJsonMissing(['name' => 'Změněná oblast']);
    }

    public function test_delete_nonexistent_field(): void
    {
        $response = $this->delete('v1.0/field/nonexistent', [], [], 'administrator');

        $response->assertStatus(404);
        $response->assertExactJson([
            'message' => 'No such field has been found.',
            'status' => 404,
            'type' => 'NotFoundException',
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        $db = new Database;
        $db->prepare(<<<'SQL'
DROP TABLE `fields`;
SQL);
        $db->execute();
        $db->prepare(<<<'SQL'
DROP TABLE `lessons_in_fields`;
SQL);
        $db->execute();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $db = new Database;
        $db->prepare(<<<'SQL'
CREATE TABLE IF NOT EXISTS `fields` (
  `id` binary(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` binary(16) NOT NULL,
  `icon` binary(16) NOT NULL,
  PRIMARY KEY (`id`)
);
SQL);
        $db->execute();
        $db->prepare(<<<'SQL'
CREATE TABLE IF NOT EXISTS `lessons_in_fields` (
  `field_id` binary(16) NOT NULL,
  `lesson_id` binary(16) NOT NULL
);
SQL);
        $db->execute();
    }
}
