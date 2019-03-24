<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

require_once($CONFIG->basepath . '/v1.0/endpoints/lessonEndpoint/list.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/lessonEndpoint/get.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/lessonEndpoint/add.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/lessonEndpoint/update.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/lessonEndpoint/delete.php');

require_once($CONFIG->basepath . '/v1.0/endpoints/lessonCompetenceEndpoint.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/lessonFieldEndpoint.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/lessonGroupEndpoint.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/lessonHistoryEndpoint.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/lessonPDFEndpoint.php');

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Role;

$lessonEndpoint = new Endpoint();
$lessonEndpoint->addSubEndpoint('competence', $lessonCompetenceEndpoint);
$lessonEndpoint->addSubEndpoint('field', $lessonFieldEndpoint);
$lessonEndpoint->addSubEndpoint('group', $lessonGroupEndpoint);
$lessonEndpoint->addSubEndpoint('history', $lessonHistoryEndpoint);
$lessonEndpoint->addSubEndpoint('pdf', $lessonPDFEndpoint);

$lessonEndpoint->setListMethod(new Role('guest'), $listLessons);
$lessonEndpoint->setGetMethod(new Role('guest'), $getLesson);
$lessonEndpoint->setAddMethod(new Role('editor'), $addLesson);
$lessonEndpoint->setUpdateMethod(new Role('editor'), $updateLesson);
$lessonEndpoint->setDeleteMethod(new Role('administrator'), $deleteLesson);
