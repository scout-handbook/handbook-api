<?php

declare(strict_types=1);

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Competence;
use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Role;
use Skaut\HandbookAPI\v1_0\Exception\InvalidArgumentTypeException;
use Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException;
use Skaut\HandbookAPI\v1_0\Exception\NotFoundException;

$competenceEndpoint = new Endpoint();

$listCompetences = function (): array {
    $SQL = <<<SQL
SELECT `id`, `number`, `name`, `description`
FROM `competences`
SQL;

    $db = new Database();
    $db->prepare($SQL);
    $db->execute();
    $id = '';
    $number = '';
    $name = '';
    $description = '';
    $db->bindColumn('id', $id);
    $db->bindColumn('number', $number);
    $db->bindColumn('name', $name);
    $db->bindColumn('description', $description);
    $competences = [];
    while ($db->fetch()) {
        $competences[Uuid::fromBytes($id)->toString()] =
            new Competence(strval($number), strval($name), strval($description));
    }
    return ['status' => 200, 'response' => $competences];
};
$competenceEndpoint->setListMethod(new Role('guest'), $listCompetences);

$addCompetence = function (Skautis $skautis, array $data): array {
    $SQL = <<<SQL
INSERT INTO `competences` (`id`, `number`, `name`, `description`)
VALUES (:id, :number, :name, :description);
SQL;

    if (!isset($data['number'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'number');
    }
    if (!isset($data['name'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'name');
    }
    $number = $data['number'];
    $name = $data['name'];
    $description = '';
    if (isset($data['description'])) {
        $description = $data['description'];
    }
    $uuid = Uuid::uuid4()->getBytes();

    $db = new Database();
    $db->prepare($SQL);
    $db->bindParam(':id', $uuid, PDO::PARAM_STR);
    $db->bindParam(':number', $number, PDO::PARAM_STR);
    $db->bindParam(':name', $name, PDO::PARAM_STR);
    $db->bindParam(':description', $description, PDO::PARAM_STR);
    $db->execute();
    return ['status' => 201];
};
$competenceEndpoint->setAddMethod(new Role('administrator'), $addCompetence);

$updateCompetence = function (Skautis $skautis, array $data): array {
    $selectSQL = <<<SQL
SELECT `number`, `name`, `description`
FROM `competences`
WHERE `id` = :id;
SQL;
    $updateSQL = <<<SQL
UPDATE `competences`
SET `number` = :number, `name` = :name, `description` = :description
WHERE `id` = :id
LIMIT 1;
SQL;

    $id = Helper::parseUuid($data['id'], 'competence')->getBytes();
    if (isset($data['number'])) {
        $number = ctype_digit($data['number']) ? intval($data['number']) : null;
        if ($number === null) {
            throw new InvalidArgumentTypeException('number', ['Integer']);
        }
    }
    if (isset($data['name'])) {
        $name = $data['name'];
    }
    if (isset($data['description'])) {
        $description = $data['description'];
    }

    $db = new Database();

    if (!isset($number) or !isset($name) or !isset($description)) {
        $db->prepare($selectSQL);
        $db->bindParam(':id', $id, PDO::PARAM_STR);
        $db->execute();
        $origNumber = '';
        $origName = '';
        $origDescription = '';
        $db->bindColumn('number', $origNumber);
        $db->bindColumn('name', $origName);
        $db->bindColumn('description', $origDescription);
        $db->fetchRequire('competence');
        if (!isset($number)) {
            $number = $origNumber;
        }
        if (!isset($name)) {
            $name = $origName;
        }
        if (!isset($description)) {
            $description = $origDescription;
        }
    }

    $db->beginTransaction();

    $db->prepare($updateSQL);
    $db->bindParam(':number', $number, PDO::PARAM_INT);
    $db->bindParam(':name', $name, PDO::PARAM_STR);
    $db->bindParam(':description', $description, PDO::PARAM_STR);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->endTransaction();
    return ['status' => 200];
};
$competenceEndpoint->setUpdateMethod(new Role('administrator'), $updateCompetence);

$deleteCompetence = function (Skautis $skautis, array $data): array {
    $deleteLessonsSQL = <<<SQL
DELETE FROM `competences_for_lessons`
WHERE `competence_id` = :competence_id;
SQL;
    $deleteSQL = <<<SQL
DELETE FROM `competences`
WHERE `id` = :id
LIMIT 1;
SQL;

    $id = Helper::parseUuid($data['id'], 'competence')->getBytes();

    $db = new Database();
    $db->beginTransaction();

    $db->prepare($deleteLessonsSQL);
    $db->bindParam(':competence_id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($deleteSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    if ($db->rowCount() != 1) {
        throw new NotFoundException("competence");
    }

    $db->endTransaction();
    return ['status' => 200];
};
$competenceEndpoint->setDeleteMethod(new Role('administrator'), $deleteCompetence);
