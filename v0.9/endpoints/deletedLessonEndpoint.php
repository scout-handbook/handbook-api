<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Role.php');

require_once($CONFIG->basepath . '/v0.9/endpoints/deletedLessonHistoryEndpoint.php');

use Ramsey\Uuid\Uuid;

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\DeletedLesson;
use Skaut\HandbookAPI\v0_9\Endpoint;

$deletedLessonEndpoint = new Endpoint();
$deletedLessonEndpoint->addSubEndpoint('history', $deletedLessonHistoryEndpoint);

$listDeletedLessons = function () : array {
    $SQL = <<<SQL
SELECT a.id, a.name
FROM lesson_history a
LEFT JOIN lessons ON a.id = lessons.id # Only deleted lessons
LEFT JOIN lesson_history b ON a.id = b.id AND a.version < b.version # Only most recent version
WHERE lessons.id IS NULL AND b.id IS NULL;
SQL;

    $db = new Database();
    $db->prepare($SQL);
    $db->execute();
    $lessons = [];
    $id = '';
    $name = '';
    $db->bindColumn('id', $id);
    $db->bindColumn('name', $name);

    while ($db->fetch()) {
        $lessons[] = new DeletedLesson($id, $name);
    }

    return ['status' => 200, 'response' => $lessons];
};
$deletedLessonEndpoint->setListMethod(new HandbookAPI\Role('administrator'), $listDeletedLessons);
