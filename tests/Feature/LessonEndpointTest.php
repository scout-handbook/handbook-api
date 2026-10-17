<?php

declare(strict_types=1);

namespace Tests\Feature;

use DateInterval;
use DateTime;
use DateTimeZone;
use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Exception\AuthenticationException;
use Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException;
use Skaut\HandbookAPI\v1_0\Role;
use Tests\TestCase;

// The lesson endpoint files are not safe to load repeatedly inside one request
// cycle - lesson.php requires the endpoint chain with require_once while sharing
// file-scope variables - so these tests call Endpoint::call directly instead of
// going through the HTTP stack like the field, group and competence tests do.
// For the same reason this class has to stay the last one alphabetically among
// the Feature tests: it loads those endpoint files into the test process
// itself, so a later class exercising the HTTP lesson stack would find them
// marked as included and skip their bodies.
final class LessonEndpointTest extends TestCase
{
    public function test_add_lesson(): void
    {
        self::overrideRole('editor');

        $result = self::lessonEndpoint()->call('POST', new Role('editor'), [
            'body' => 'Text kapitoly',
            'name' => 'Testovací kapitola',
        ]);

        $this->assertSame(201, $result['status']);
    }

    public function test_add_lesson_without_auth(): void
    {
        $this->expectException(AuthenticationException::class);

        self::lessonEndpoint()->call('POST', new Role('editor'), ['name' => 'Nepovolená kapitola']);
    }

    public function test_add_lesson_without_name(): void
    {
        $this->assertNameRejected('POST', []);
    }

    public function test_add_lesson_with_empty_name(): void
    {
        $this->assertNameRejected('POST', ['name' => '']);
    }

    public function test_add_lesson_with_whitespace_name(): void
    {
        $this->assertNameRejected('POST', ['name' => '   ']);
    }

    public function test_update_lesson_with_empty_name(): void
    {
        $this->assertNameRejected('PUT', [
            'id' => '00000000-0000-0000-0000-000000000000',
            'name' => '',
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        $db = new Database;
        $db->prepare(<<<'SQL'
DROP TABLE `lessons`;
SQL);
        $db->execute();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $db = new Database;
        $db->prepare(<<<'SQL'
CREATE TABLE IF NOT EXISTS `lessons` (
  `id` binary(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  `version` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `body` text NOT NULL,
  PRIMARY KEY (`id`)
);
SQL);
        $db->execute();
    }

    protected function tearDown(): void
    {
        global $_TEST_OVERRIDE;

        unset($_TEST_OVERRIDE);
        $_COOKIE = [];

        parent::tearDown();
    }

    /** @param  array<string, string>  $data */
    private function assertNameRejected(string $method, array $data): void
    {
        self::overrideRole('editor');

        try {
            self::lessonEndpoint()->call($method, new Role('editor'), $data);
            $this->fail('An empty or missing name should have been rejected.');
        } catch (MissingArgumentException $e) {
            $handled = $e->handle();
            $this->assertSame('MissingArgumentException', $handled['type']);
            $this->assertSame(400, $handled['status']);
            $this->assertSame('POST argument "name" must be provided.', $handled['message']);
        }
    }

    private static function lessonEndpoint(): Endpoint
    {
        // The endpoint chain below runs only on the first call (require_once),
        // so the endpoint has to live in the global scope to be found again.
        // (The mutex endpoint used by update and delete declares itself global
        // in mutexEndpoint.php, so it does not have to be listed here.)
        global $lessonEndpoint;

        if (! defined('_API_EXEC')) {
            define('_API_EXEC', 1);
        }

        // Re-running api-config.php on every call keeps $CONFIG available in the
        // scope where the endpoint files below are executed.
        require $_SERVER['DOCUMENT_ROOT'].'/api-config.php';

        // The same location tests/api-config.php keeps in $CONFIG->basepath,
        // written out because the static analysers cannot see through the require.
        $endpointPath = $_SERVER['DOCUMENT_ROOT'].'/../legacy/v1.0/endpoints';

        // lessonPDFEndpoint.php requires competenceEndpoint.php and fieldEndpoint.php
        // with require_once, so if an earlier test class has already loaded them they
        // would not be executed again and its closures would bind undefined variables.
        // Both files only create endpoint objects, so loading them again here is safe.
        require $endpointPath.'/competenceEndpoint.php';
        require $endpointPath.'/fieldEndpoint.php';

        require_once $endpointPath.'/lessonEndpoint.php';

        return $lessonEndpoint;
    }

    private static function overrideRole(string $role): void
    {
        global $_TEST_OVERRIDE;

        $_TEST_OVERRIDE = $role;
        $_COOKIE['skautis_token'] = 'TOKEN';
        $_COOKIE['skautis_timeout'] = (new DateTime('now', new DateTimeZone('UTC')))
            ->add(new DateInterval('PT10M'))
            ->format('U');
    }
}
