<?php

namespace Tests\Feature;

use Skaut\HandbookAPI\v1_0\Database;
use Tests\LegacyEndpointTestCase;

/** @SuppressWarnings("PHPMD.TooManyPublicMethods") */
class CompetenceEndpointTest extends LegacyEndpointTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $SQL1 = <<<SQL
CREATE TABLE IF NOT EXISTS `competences` (
  `id` binary(16) NOT NULL,
  `number` varchar(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
);
SQL;
        $SQL2 = <<<SQL
CREATE TABLE IF NOT EXISTS `competences_for_lessons` (
  `lesson_id` binary(16) NOT NULL,
  `competence_id` binary(16) NOT NULL
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

        $SQL1 = <<<SQL
DROP TABLE `competences`;
SQL;
        $SQL2 = <<<SQL
DROP TABLE `competences_for_lessons`;
SQL;
        $db = new Database();
        $db->prepare($SQL1);
        $db->execute();
        $db->prepare($SQL2);
        $db->execute();
    }

    public function testEmptyList(): void
    {
        $response = $this->get('v1.0/competence');

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200, 'response' => []]);
    }

    public function testAddCompetence(): void
    {
        $response = $this->post('v1.0/competence', ['name' => 'Nová kompetence', 'number' => 42], [], 'administrator');

        $response->assertStatus(201);
        $response->assertExactJson(['status' => 201]);
    }

    public function testList(): void
    {
        $response = $this->get('v1.0/competence');

        $response->assertStatus(200);
        $response->assertJson(['status' => 200]);
        $response->assertJsonFragment(['number' => '42', 'name' => 'Nová kompetence', 'description' => '']);
    }

    public function testAddCompetenceWithoutAuth(): void
    {
        $response = $this->post('v1.0/competence', ['number' => 42], []);

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'status' => 403,
                'type' => 'AuthenticationException',
                'message' => 'Authentication failed.'
            ]
        );
    }

    public function testAddCompetenceWithoutName(): void
    {
        $response = $this->post('v1.0/competence', ['number' => 42], [], 'administrator');

        $response->assertStatus(400);
        $response->assertExactJson(
            [
                'status' => 400,
                'type' => 'MissingArgumentException',
                'message' => 'POST argument "name" must be provided.'
            ]
        );
    }

    public function testAddCompetenceWithoutNumber(): void
    {
        $response = $this->post('v1.0/competence', ['name' => 'Nová kompetence'], [], 'administrator');

        $response->assertStatus(400);
        $response->assertExactJson(
            [
                'status' => 400,
                'type' => 'MissingArgumentException',
                'message' => 'POST argument "number" must be provided.'
            ]
        );
    }

    public function testAddCompetenceWithDescription(): void
    {
        $response = $this->post(
            'v1.0/competence',
            ['name' => 'Nová kompetence 2', 'number' => 43, 'description' => 'Popis'],
            [],
            'administrator'
        );

        $response->assertStatus(201);
        $response->assertExactJson(['status' => 201]);
    }

    public function testList2(): void
    {
        $response = $this->get('v1.0/competence');

        $response->assertStatus(200);
        $response->assertJson(['status' => 200]);
        $response->assertJsonFragment(['number' => '42', 'name' => 'Nová kompetence', 'description' => '']);
        $response->assertJsonFragment(['number' => '43', 'name' => 'Nová kompetence 2', 'description' => 'Popis']);
    }

    public function testUpdateCompetence(): void
    {
        $response = $this->get('v1.0/competence', [], []);

        $response->assertStatus(200);
        $competenceId = key(
            array_filter(
                $response['response'],
                function ($competence) {
                    return $competence['number'] === '42';
                }
            )
        );

        $response = $this->put(
            'v1.0/competence/' . $competenceId,
            ['description' => 'Nový popis'],
            [],
            'administrator'
        );

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200]);

        $response = $this->get('v1.0/competence');

        $response->assertStatus(200);
        $response->assertJsonFragment(['number' => '42', 'name' => 'Nová kompetence', 'description' => 'Nový popis']);
    }

    public function testUpdateCompetenceWithoutAuth(): void
    {
        $response = $this->get('v1.0/competence', [], []);

        $response->assertStatus(200);
        $competenceId = key($response['response']);

        $response = $this->put(
            'v1.0/competence/' . $competenceId,
            ['description' => 'Nový popis'],
            []
        );

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'status' => 403,
                'type' => 'AuthenticationException',
                'message' => 'Authentication failed.'
            ]
        );
    }

    public function testUpdateNonexistentCompetence(): void
    {
        $response = $this->put(
            'v1.0/competence/missing',
            ['description' => 'Nový popis'],
            [],
            'administrator'
        );

        $response->assertStatus(404);
        $response->assertExactJson(
            [
                'status' => 404,
                'type' => 'NotFoundException',
                'message' => 'No such competence has been found.'
            ]
        );
    }

    public function testDeleteCompetence(): void
    {
        $response = $this->get('v1.0/competence', [], []);

        $response->assertStatus(200);
        $competenceId = key(
            array_filter(
                $response['response'],
                function ($competence) {
                    return $competence['number'] === '42';
                }
            )
        );

        $response = $this->delete('v1.0/competence/' . $competenceId, [], [], 'administrator');

        $response->assertStatus(200);
        $response->assertExactJson(['status' => 200]);

        $response = $this->get('v1.0/competence');

        $response->assertStatus(200);
        $response->assertJsonMissing(['number' => '42', 'name' => 'Nová kompetence', 'description' => '']);
    }

    public function testDeleteCompetenceWithoutAuthentication(): void
    {
        $response = $this->get('v1.0/competence', [], []);

        $response->assertStatus(200);
        $competenceId = key($response['response']);

        $response = $this->delete('v1.0/competence/' . $competenceId, [], []);

        $response->assertStatus(403);
        $response->assertExactJson(
            [
                'status' => 403,
                'type' => 'AuthenticationException',
                'message' => 'Authentication failed.'
            ]
        );
    }

    public function testDeleteNonexistentCompetence(): void
    {
        $response = $this->delete('v1.0/competence/nonexistent', [], [], 'administrator');

        $response->assertStatus(404);
        $response->assertExactJson(
            [
                'status' => 404,
                'type' => 'NotFoundException',
                'message' => 'No such competence has been found.'
            ]
        );
    }
}
