<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

require_once($CONFIG->basepath . '/v0.9/endpoints/accountEndpoint.php');

require_once($CONFIG->basepath . '/v0.9/endpoints/lessonEndpoint/list.php');
require_once($CONFIG->basepath . '/v0.9/endpoints/lessonEndpoint/get.php');
require_once($CONFIG->basepath . '/v0.9/endpoints/lessonEndpoint/add.php');
require_once($CONFIG->basepath . '/v0.9/endpoints/lessonEndpoint/update.php');
require_once($CONFIG->basepath . '/v0.9/endpoints/lessonEndpoint/delete.php');

require_once($CONFIG->basepath . '/v0.9/endpoints/lessonCompetenceEndpoint.php');
require_once($CONFIG->basepath . '/v0.9/endpoints/lessonFieldEndpoint.php');
require_once($CONFIG->basepath . '/v0.9/endpoints/lessonGroupEndpoint.php');
require_once($CONFIG->basepath . '/v0.9/endpoints/lessonHistoryEndpoint.php');
require_once($CONFIG->basepath . '/v0.9/endpoints/lessonPDFEndpoint.php');

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Endpoint;
use Skaut\HandbookAPI\v0_9\Role;

$lessonEndpoint = new Endpoint();
$lessonEndpoint->addSubEndpoint('competence', $lessonCompetenceEndpoint);
$lessonEndpoint->addSubEndpoint('field', $lessonFieldEndpoint);
$lessonEndpoint->addSubEndpoint('group', $lessonGroupEndpoint);
$lessonEndpoint->addSubEndpoint('history', $lessonHistoryEndpoint);
$lessonEndpoint->addSubEndpoint('pdf', $lessonPDFEndpoint);

function checkLessonGroup(UuidInterface $lessonId, bool $overrideGroup = false) : bool
{
    global $accountEndpoint;

    $groupSQL = <<<SQL
SELECT `group_id` FROM `groups_for_lessons`
WHERE `lesson_id` = :lesson_id;
SQL;

    $loginState = $accountEndpoint->call('GET', new Role('guest'), ['no-avatar' => 'true']);

    if ($loginState['status'] == '200') {
        if ($overrideGroup and in_array($loginState['response']['role'], ['editor', 'administrator', 'superuser'])) {
            return true;
        }
        $groups = $loginState['response']['groups'];
        $groups[] = '00000000-0000-0000-0000-000000000000';
    } else {
        $groups = ['00000000-0000-0000-0000-000000000000'];
    }
    array_walk($groups, '\Ramsey\Uuid\Uuid::fromString');

    $db = new Database();
    $db->prepare($groupSQL);
    $lessonId = $lessonId->getBytes();
    $db->bindParam(':lesson_id', $lessonId, PDO::PARAM_STR);
    $db->execute();
    $groupId = '';
    $db->bindColumn('group_id', $groupId);
    while ($db->fetch()) {
        if (in_array(Uuid::fromBytes(strval($groupId)), $groups)) {
            return true;
        }
    }
    return false;
}

$lessonEndpoint->setListMethod(new Role('guest'), $listLessons);
$lessonEndpoint->setGetMethod(new Role('guest'), $getLesson);
$lessonEndpoint->setAddMethod(new Role('editor'), $addLesson);
$lessonEndpoint->setUpdateMethod(new Role('editor'), $updateLesson);
$lessonEndpoint->setDeleteMethod(new Role('administrator'), $deleteLesson);
