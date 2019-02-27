<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');

require_once($CONFIG->basepath . '/v0.9/internal/exceptions/MissingArgumentException.php');

use Ramsey\Uuid\Uuid;

use Skaut\HandbookAPI\v0_9\Database;

$addLesson = function (Skautis\Skautis $skautis, array $data) : array {
    $SQL = <<<SQL
INSERT INTO lessons (id, name, body)
VALUES (:id, :name, :body);
SQL;

    if (!isset($data['name'])) {
        throw new HandbookAPI\MissingArgumentException(HandbookAPI\MissingArgumentException::POST, 'name');
    }
    $name = $data['name'];
    $body = '';
    if (isset($data['body'])) {
        $body = $data['body'];
    }
    $uuid = Uuid::uuid4();
    $id = $uuid->getBytes();

    $db = new Database();
    $db->prepare($SQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->bindParam(':name', $name, PDO::PARAM_STR);
    $db->bindParam(':body', $body, PDO::PARAM_STR);
    $db->execute();
    return ['status' => 201, 'response' => $uuid];
};
