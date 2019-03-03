<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

require_once($CONFIG->basepath . '/v0.9/endpoints/mutexEndpoint.php');

use Skautis\Skautis;

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Helper;
use Skaut\HandbookAPI\v0_9\Role;
use Skaut\HandbookAPI\v0_9\Exception\NotFoundException;
use Skaut\HandbookAPI\v0_9\Exception\NotLockedException;

$updateLesson = function (Skautis $skautis, array $data) : array {
    $selectSQL = <<<SQL
SELECT name, body
FROM lessons
WHERE id = :id;
SQL;
    $copySQL = <<<SQL
INSERT INTO lesson_history (id, name, version, body)
SELECT id, name, version, body
FROM lessons
WHERE id = :id;
SQL;
    $updateSQL = <<<SQL
UPDATE lessons
SET name = :name, version = CURRENT_TIMESTAMP(3), body = :body
WHERE id = :id
LIMIT 1;
SQL;

    global $mutexEndpoint;
    try {
        $mutexEndpoint->call('DELETE', new Role('editor'), ['id' => $data['id']]);
    } catch (NotFoundException $e) {
        throw new NotLockedException();
    }

    $id = Helper::parseUuid($data['id'], 'lesson')->getBytes();
    if (isset($data['name'])) {
        $name = $data['name'];
    }
    if (isset($data['body'])) {
        $body = $data['body'];
    }

    $db = new Database();

    if (!isset($name) or !isset($body)) {
        $db->prepare($selectSQL);
        $db->bindParam(':id', $id, PDO::PARAM_STR);
        $db->execute();
        $origName = '';
        $origBody = '';
        $db->bindColumn('name', $origName);
        $db->bindColumn('body', $origBody);
        if (!$db->fetch()) {
            throw new NotFoundException('lesson');
        }
        if (!isset($name)) {
            $name = $origName;
        }
        if (!isset($body)) {
            $body = $origBody;
        }
    }

    $db->beginTransaction();

    $db->prepare($copySQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($updateSQL);
    $db->bindParam(':name', $name, PDO::PARAM_STR);
    $db->bindParam(':body', $body, PDO::PARAM_STR);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    if ($db->rowCount() != 1) {
        throw new NotFoundException("lesson");
    }

    $db->endTransaction();
    return ['status' => 200];
};
