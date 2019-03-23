<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException;

$addLesson = function (Skautis $skautis, array $data) : array {
    $SQL = <<<SQL
INSERT INTO lessons (id, name, body)
VALUES (:id, :name, :body);
SQL;

    if (!isset($data['name'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'name');
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
