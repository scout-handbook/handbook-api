<?php

declare(strict_types=1);

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Field;
use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Role;
use Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException;
use Skaut\HandbookAPI\v1_0\Exception\NotFoundException;

$fieldEndpoint = new Endpoint();

$listFields = function (Skautis $skautis, array $data) : array {
    $fieldSQL = <<<SQL
SELECT `id`, `name`, `description`, `image`, `icon`
FROM `fields`;
SQL;
    $lessonSQL = <<<SQL
SELECT `lesson_id`
FROM `lessons_in_fields`
WHERE `field_id` = :field_id;
SQL;

    $overrideGroup = (isset($data['override-group']) and $data['override-group'] == 'true');

    $db = new Database();
    $db->prepare($fieldSQL);
    $db->execute();
    $field_id = '';
    $field_name = '';
    $field_description = '';
    $field_image = '';
    $field_icon = '';
    $db->bindColumn('id', $field_id);
    $db->bindColumn('name', $field_name);
    $db->bindColumn('description', $field_description);
    $db->bindColumn('image', $field_image);
    $db->bindColumn('icon', $field_icon);
    $fields = [];
    while ($db->fetch()) {
        $newField = new Field($field_name, $field_description, $field_image, $field_icon);

        $db2 = new Database();
        $db2->prepare($lessonSQL);
        $db2->bindParam(':field_id', $field_id, PDO::PARAM_STR);
        $db2->execute();
        $lesson_id = '';
        $db2->bindColumn('lesson_id', $lesson_id);
        while ($db2->fetch()) {
            if (Helper::checkLessonGroup(Uuid::fromBytes($lesson_id), $overrideGroup)) {
                $newField->addLesson($lesson_id);
            }
        }

        $fields[Uuid::fromBytes($field_id)->toString()] = $newField;
    }
    return ['status' => 200, 'response' => $fields];
};
$fieldEndpoint->setListMethod(new Role('guest'), $listFields);

$addField = function (Skautis $skautis, array $data) : array {
    $SQL = <<<SQL
INSERT INTO `fields` (`id`, `name`, `description`, `image`, `icon`)
VALUES (:id, :name, :description, :image, :icon);
SQL;

    $uuid = Uuid::uuid4()->getBytes();
    if (!isset($data['name'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'name');
    }
    $name = $data['name'];
    if (!isset($data['description'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'description');
    }
    $description = $data['description'];
    if (!isset($data['image'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'image');
    }
    $image = Helper::parseUuid($data['image'], 'image')->getBytes();
    if (!isset($data['icon'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'icon');
    }
    $icon = Helper::parseUuid($data['icon'], 'icon')->getBytes();

    $db = new Database();
    $db->prepare($SQL);
    $db->bindParam(':id', $uuid, PDO::PARAM_STR);
    $db->bindParam(':name', $name, PDO::PARAM_STR);
    $db->bindParam(':description', $description, PDO::PARAM_STR);
    $db->bindParam(':image', $image, PDO::PARAM_STR);
    $db->bindParam(':icon', $icon, PDO::PARAM_STR);
    $db->execute();
    return ['status' => 201];
};
$fieldEndpoint->setAddMethod(new Role('administrator'), $addField);

$updateField = function (Skautis $skautis, array $data) : array {
    $SQL = <<<SQL
UPDATE `fields`
SET `name` = :name, `description` = :description, `image` = :image, `icon` = :icon
WHERE `id` = :id
LIMIT 1;
SQL;

    $id = Helper::parseUuid($data['id'], 'field')->getBytes();
    if (!isset($data['name'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'name');
    }
    $name = $data['name'];
    if (!isset($data['description'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'description');
    }
    $description = $data['description'];
    if (!isset($data['image'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'image');
    }
    $image = Helper::parseUuid($data['image'], 'image')->getBytes();
    if (!isset($data['icon'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'icon');
    }
    $icon = Helper::parseUuid($data['icon'], 'icon')->getBytes();

    $db = new Database();
    $db->beginTransaction();

    $db->prepare($SQL);
    $db->bindParam(':name', $name, PDO::PARAM_STR);
    $db->bindParam(':description', $description, PDO::PARAM_STR);
    $db->bindParam(':image', $image, PDO::PARAM_STR);
    $db->bindParam(':icon', $icon, PDO::PARAM_STR);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->endTransaction();
    return ['status' => 200];
};
$fieldEndpoint->setUpdateMethod(new Role('administrator'), $updateField);

$deleteField = function (Skautis $skautis, array $data) : array {
    $deleteLessonsSQL = <<<SQL
DELETE FROM `lessons_in_fields`
WHERE `field_id` = :field_id;
SQL;
    $deleteSQL = <<<SQL
DELETE FROM `fields`
WHERE `id` = :id
LIMIT 1;
SQL;

    $id = Helper::parseUuid($data['id'], 'field')->getBytes();

    $db = new Database();
    $db->beginTransaction();

    $db->prepare($deleteLessonsSQL);
    $db->bindParam(':field_id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($deleteSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    if ($db->rowCount() != 1) {
        throw new NotFoundException("field");
    }

    $db->endTransaction();
    return ['status' => 200];
};
$fieldEndpoint->setDeleteMethod(new Role('administrator'), $deleteField);
