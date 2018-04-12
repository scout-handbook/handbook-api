<?php declare(strict_types = 1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Endpoint.php');
require_once($CONFIG->basepath . '/v0.9/internal/Role.php');

$mutexEndpoint = new HandbookAPI\Endpoint();

$addMutex = function(Skautis\Skautis $skautis) : array
{
	$selectSQL = <<<SQL
	SELECT DISTINCT UNIX_TIMESTAMP(mutexes.timeout), mutexes.holder, users.name
FROM mutexes
LEFT JOIN users ON mutexes.holder = users.id
WHERE mutexes.id = :id;
SQL;
	$deleteSQL = <<<SQL
DELETE FROM mutexes
WHERE id = :id;
SQL;
	$insertSQL = <<<SQL
INSERT INTO mutexes (id, timeout, holder)
VALUES (:id, FROM_UNIXTIME(:timeout), :holder);
SQL;

	$id = HandbookAPI\Helper::parseUuid($data['id'], 'resource')->getBytes();
	$timeout = time() + 1800;
	if(isset($_COOKIE['skautis_timeout']))
	{
		$timeout = ctype_digit($_COOKIE['skautis_timeout']) ? intval($_COOKIE['skautis_timeout']) : null;
		if($timeout === null || abs($timeout - time()) > 3600)
		{
			throw new HandbookAPI\InvalidArgumentTypeException('number', ['Unix timestamp']);
		}
	}
	$userId = $skautis->UserManagement->LoginDetail()->ID_Person;

	$db = new HandbookAPI\Database();
	$db->beginTransaction();

	$db->prepare($selectSQL);
	$db->bindParam(':id', $id, PDO::PARAM_STR);
	$db->execute();
	$origTimeout = '';
	$origHolder = '';
	$origHolderName = '';
	$db->bindColumn(1, $origTimeout);
	$db->bindColumn('holder', $origHolder);
	$db->bindColumn('name', $origHolderName);
	if($db->fetch())
	{
		if($origHolder != $userId && $origTimeout > time())
		{
			return ['status' => 409, 'response' => ['holder' => $origHolderName]];
		}
	}

	$db->prepare($deleteSQL);
	$db->bindParam(':id', $id, PDO::PARAM_STR);
	$db->execute();

	$db->prepare($insertSQL);
	$db->bindParam(':id', $id, PDO::PARAM_STR);
	$db->bindParam(':timeout', $timeout, PDO::PARAM_INT);
	$db->bindParam(':holder', $userId, PDO::PARAM_INT);
	$db->execute();

	$db->endTransaction();
	return ['status' => 200];
};
$mutexEndpoint->setAddMethod(new HandbookAPI\Role('editor'), $addMutex);
