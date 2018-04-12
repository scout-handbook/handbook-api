<?php declare(strict_types = 1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Endpoint.php');
require_once($CONFIG->basepath . '/v0.9/internal/Role.php');

$mutexEndpoint = new HandbookAPI\Endpoint();

$addMutex = function(Skautis\Skautis $skautis) : array
{
	return ['status' => 200];
};
$mutexEndpoint->setAddMethod(new HandbookAPI\Role('editor'), $addMutex);
