<?php

namespace Tests\Feature;

use Skaut\HandbookAPI\v1_0\Database;
use Tests\LegacyEndpointTestCase;

/** @SuppressWarnings("PHPMD.TooManyPublicMethods") */
class FieldEndpointTest extends LegacyEndpointTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $SQL1 = <<<SQL
CREATE TABLE IF NOT EXISTS `fields` (
  `id` binary(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` binary(16) NOT NULL,
  `icon` binary(16) NOT NULL,
  PRIMARY KEY (`id`)
);
SQL;
        $SQL2 = <<<SQL
CREATE TABLE IF NOT EXISTS `lessons_in_fields` (
  `field_id` binary(16) NOT NULL,
  `lesson_id` binary(16) NOT NULL
);
SQL;
        $db = new Database();
        $db->prepare($SQL1);
        $db->execute();
        $db->prepare($SQL2);
        $db->execute();
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        $SQL1 = "DROP TABLE `fields`;";
        $SQL2 = "DROP TABLE `lessons_in_fields`;";
        $db = new Database();
        $db->prepare($SQL1);
        $db->execute();
        $db->prepare($SQL2);
        $db->execute();
    }

    public function testEmptyList(): void
    {
        $response = $this->get('v1.0/field');

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200, 'response' => []]);
    }

    public function testAddField(): void
    {
        $response = $this->post(
            'v1.0/field',
            [
                'name' => 'Testovací oblast',
                'description' => 'Popis',
                'image' => '00000000-0000-0000-0000-000000000000',
                'icon' => '00000000-0000-0000-0000-000000000000'
            ],
            [],
            'administrator'
        );

        $response->assertStatus(201);
        $response->assertJson(['status' => 201]);
    }

    public function testListFields(): void
    {
        $response = $this->get('v1.0/field');
        $response->assertStatus(200);
        $response->assertJson(['status' => 200]);
        $response->assertJsonFragment(['name' => 'Testovací oblast']);
    }

    public function testAddFieldWithoutAuth(): void
    {
        $response = $this->post('v1.0/field', ['name' => 'Nepovolená oblast'], []);
        $response->assertStatus(403);
        $response->assertExactJson([
            'status' => 403,
            'type' => 'AuthenticationException',
            'message' => 'Authentication failed.'
        ]);
    }

    public function testAddFieldWithoutName(): void
    {
        $response = $this->post('v1.0/field', [], [], 'administrator');
        $response->assertStatus(400);
        $response->assertExactJson([
            'status' => 400,
            'type' => 'MissingArgumentException',
            'message' => 'POST argument "name" must be provided.'
        ]);
    }

    public function testUpdateField(): void
    {
        $response = $this->get('v1.0/field', [], 'editor');
        $response->assertStatus(200);

        $fieldId = key($response['response']);

        $response = $this->put(
            'v1.0/field/' . $fieldId,
            [
                'name' => 'Změněná oblast',
                'description' => 'Změněný popis',
                'image' => '00000000-0000-0000-0000-000000000001',
                'icon' => '00000000-0000-0000-0000-000000000001'
            ],
            [],
            'administrator'
        );

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200]);

        $response = $this->get('v1.0/field', [], 'editor');
        $response->assertJsonFragment(
            [
                'name' => 'Změněná oblast',
                'description' => 'Změněný popis',
                'image' => '00000000-0000-0000-0000-000000000001',
                'icon' => '00000000-0000-0000-0000-000000000001'
            ]
        );
    }

    public function testUpdateFieldWithoutName(): void
    {
        $response = $this->get('v1.0/field', [], 'editor');
        $response->assertStatus(200);

        $fieldId = key($response['response']);

        $response = $this->put(
            'v1.0/field/' . $fieldId,
            [],
            [],
            'administrator'
        );

        $response->assertStatus(400);
        $response->assertExactJson([
            'status' => 400,
            'type' => 'MissingArgumentException',
            'message' => 'POST argument "name" must be provided.'
        ]);
    }

    public function testUpdateFieldWithoutAuth(): void
    {
        $response = $this->get('v1.0/field', [], 'editor');
        $response->assertStatus(200);

        $fieldId = key($response['response']);

        $response = $this->put('v1.0/field/' . $fieldId, ['name' => 'Neoprávněná změna'], []);

        $response->assertStatus(403);
        $response->assertExactJson([
            'status' => 403,
            'type' => 'AuthenticationException',
            'message' => 'Authentication failed.'
        ]);
    }

    public function testUpdateNonexistentField(): void
    {
        $response = $this->put(
            'v1.0/field/nonexistent',
            ['name' => 'Nová oblast'],
            [],
            'administrator'
        );

        $response->assertStatus(404);
        $response->assertExactJson([
            'status' => 404,
            'type' => 'NotFoundException',
            'message' => 'No such field has been found.'
        ]);
    }

    public function testDeleteFieldWithoutAuth(): void
    {
        $response = $this->get('v1.0/field', [], 'editor');
        $fieldId = key($response['response']);

        $response = $this->delete('v1.0/field/' . $fieldId, [], []);

        $response->assertStatus(403);
        $response->assertExactJson([
            'status' => 403,
            'type' => 'AuthenticationException',
            'message' => 'Authentication failed.'
        ]);
    }

    public function testDeleteField(): void
    {
        $response = $this->get('v1.0/field', [], 'editor');
        $fieldId = key($response['response']);

        $response = $this->delete('v1.0/field/' . $fieldId, [], [], 'administrator');
        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200]);

        $response = $this->get('v1.0/field', [], 'editor');
        $response->assertJsonMissing(['name' => 'Změněná oblast']);
    }

    public function testDeleteNonexistentField(): void
    {
        $response = $this->delete('v1.0/field/nonexistent', [], [], 'administrator');

        $response->assertStatus(404);
        $response->assertExactJson([
            'status' => 404,
            'type' => 'NotFoundException',
            'message' => 'No such field has been found.'
        ]);
    }
}
