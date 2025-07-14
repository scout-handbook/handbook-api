<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @SuppressWarnings("PHPMD.TooManyPublicMethods") */
final class CompetenceEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_empty_list(): void
    {
        $response = $this->get('/API/v1.0/competence');
        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200, 'response' => []]);
    }

    public function test_add_competence(): void
    {
        $response = $this->post(
            '/API/v1.0/competence',
            ['name' => 'Nová kompetence', 'number' => '42'],
        );
        $response->assertStatus(201);
        $response->assertExactJson(['status' => 201]);

        $response = $this->get('/API/v1.0/competence');
        $response->assertJson(['status' => 200]);
        $response->assertJsonFragment(['number' => '42', 'name' => 'Nová kompetence', 'description' => '']);
    }

    public function test_add_competence_without_auth(): void
    {
        $response = $this->post('/API/v1.0/competence', ['number' => '42']);

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'message' => 'Authentication failed.',
                'status' => 403,
                'type' => 'AuthenticationException',
            ],
        );
    }

    /*
    public function test_add_competence_without_name(): void
    {
        $response = $this->post('v1.0/competence', ['number' => '42'], [], 'administrator');

        $response->assertStatus(400);
        $response->assertExactJson(
            [
                'message' => 'POST argument "name" must be provided.',
                'status' => 400,
                'type' => 'MissingArgumentException',
            ],
        );
    }

    public function test_add_competence_without_number(): void
    {
        $response = $this->post('v1.0/competence', ['name' => 'Nová kompetence'], [], 'administrator');

        $response->assertStatus(400);
        $response->assertExactJson(
            [
                'message' => 'POST argument "number" must be provided.',
                'status' => 400,
                'type' => 'MissingArgumentException',
            ],
        );
    }

    public function test_add_competence_with_description(): void
    {
        $response = $this->post(
            'v1.0/competence',
            ['name' => 'Nová kompetence 2', 'number' => '43', 'description' => 'Popis'],
            [],
            'administrator',
        );

        $response->assertStatus(201);
        $response->assertExactJson(['status' => 201]);
    }

    public function test_list2(): void
    {
        $response = $this->get('v1.0/competence');

        $response->assertStatus(200);
        $response->assertJson(['status' => 200]);
        $response->assertJsonFragment(['number' => '42', 'name' => 'Nová kompetence', 'description' => '']);
        $response->assertJsonFragment(['number' => '43', 'name' => 'Nová kompetence 2', 'description' => 'Popis']);
    }

    public function test_update_competence(): void
    {
        $response = $this->get('v1.0/competence', []);

        $response->assertStatus(200);
        $competenceId = key(
            array_filter(
                $response['response'],
                static fn ($competence) => $competence['number'] === '42',
            ),
        );

        $response = $this->put(
            'v1.0/competence/'.$competenceId,
            ['description' => 'Nový popis'],
            [],
            'administrator',
        );

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200]);

        $response = $this->get('v1.0/competence');

        $response->assertStatus(200);
        $response->assertJsonFragment(['number' => '42', 'name' => 'Nová kompetence', 'description' => 'Nový popis']);
    }

    public function test_update_competence_without_auth(): void
    {
        $response = $this->get('v1.0/competence', []);

        $response->assertStatus(200);
        $competenceId = key($response['response']);

        $response = $this->put(
            'v1.0/competence/'.$competenceId,
            ['description' => 'Nový popis'],
            [],
        );

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'message' => 'Authentication failed.',
                'status' => 403,
                'type' => 'AuthenticationException',
            ],
        );
    }

    public function test_update_nonexistent_competence(): void
    {
        $response = $this->put(
            'v1.0/competence/missing',
            ['description' => 'Nový popis'],
            [],
            'administrator',
        );

        $response->assertStatus(404);
        $response->assertExactJson(
            [
                'message' => 'No such competence has been found.',
                'status' => 404,
                'type' => 'NotFoundException',
            ],
        );
    }

    public function test_delete_competence(): void
    {
        $response = $this->get('v1.0/competence', []);

        $response->assertStatus(200);
        $competenceId = key(
            array_filter(
                $response['response'],
                static fn ($competence) => $competence['number'] === '42',
            ),
        );

        $response = $this->delete('v1.0/competence/'.$competenceId, [], [], 'administrator');

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200]);

        $response = $this->get('v1.0/competence');

        $response->assertStatus(200);
        $response->assertJsonMissing(['number' => '42', 'name' => 'Nová kompetence', 'description' => '']);
    }

    public function test_delete_competence_without_authentication(): void
    {
        $response = $this->get('v1.0/competence', []);

        $response->assertStatus(200);
        $competenceId = key($response['response']);

        $response = $this->delete('v1.0/competence/'.$competenceId, [], []);

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'message' => 'Authentication failed.',
                'status' => 403,
                'type' => 'AuthenticationException',
            ],
        );
    }

    public function test_delete_nonexistent_competence(): void
    {
        $response = $this->delete('v1.0/competence/nonexistent', [], [], 'administrator');

        $response->assertStatus(404);
        $response->assertExactJson(
            [
                'message' => 'No such competence has been found.',
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
DROP TABLE `competences`;
SQL);
        $db->execute();
        $db->prepare(<<<'SQL'
DROP TABLE `competences_for_lessons`;
SQL);
        $db->execute();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $db = new Database;
        $db->prepare(<<<'SQL'
CREATE TABLE IF NOT EXISTS `competences` (
  `id` binary(16) NOT NULL,
  `number` varchar(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
);
SQL);
        $db->execute();
        $db->prepare(<<<'SQL'
CREATE TABLE IF NOT EXISTS `competences_for_lessons` (
  `lesson_id` binary(16) NOT NULL,
  `competence_id` binary(16) NOT NULL
);
SQL);
        $db->execute();
    }
    */
}
