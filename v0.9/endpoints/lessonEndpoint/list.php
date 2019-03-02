<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Skautis\Skautis;

use function Skaut\HandbookAPI\v0_9\Lesson_cmp;
use function Skaut\HandbookAPI\v0_9\LessonContainer_cmp;
use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Field;
use Skaut\HandbookAPI\v0_9\Lesson;
use Skaut\HandbookAPI\v0_9\LessonContainer;

function populateContainer(
    Database $db,
    LessonContainer $container,
    bool $overrideGroup = false
) : void {
    $competenceSQL = <<<SQL
SELECT DISTINCT competences.id, competences.number
FROM competences
JOIN competences_for_lessons ON competences.id = competences_for_lessons.competence_id
WHERE competences_for_lessons.lesson_id = :lesson_id
ORDER BY competences.number;
SQL;

    $db->execute();
    $lessonId = '';
    $lessonName = '';
    $lessonVersion = '';
    $db->bindColumn('id', $lessonId);
    $db->bindColumn('name', $lessonName);
    $db->bindColumn(3, $lessonVersion);

    while ($db->fetch()) {
        if (checkLessonGroup(Uuid::fromBytes($lessonId), $overrideGroup)) {
            // Create a new Lesson in the newly-created Field
            $container->lessons[] = new Lesson($lessonId, $lessonName, floatval($lessonVersion));

            // Find out the competences this Lesson belongs to
            $db2 = new Database();
            $db2->prepare($competenceSQL);
            $db2->bindParam(':lesson_id', $lessonId, PDO::PARAM_STR);
            $db2->execute();
            $competenceId = '';
            $competenceNumber = '';
            $db2->bindColumn('id', $competenceId);
            $db2->bindColumn('number', $competenceNumber);
            end($container->lessons)->lowestCompetence = 0;
            if ($db2->fetch()) {
                end($container->lessons)->lowestCompetence = intval($competenceNumber);
                end($container->lessons)->competences[] = $competenceId;
            }
            while ($db2->fetch()) {
                end($container->lessons)->competences[] = $competenceId;
            }
        }
    }
}

$listLessons = function (Skautis $skautis, array $data) : array {
    $fieldSQL = <<<SQL
SELECT id, name
FROM fields;
SQL;
    $anonymousSQL = <<<SQL
SELECT DISTINCT lessons.id, lessons.name, UNIX_TIMESTAMP(lessons.version)
FROM lessons
LEFT JOIN lessons_in_fields ON lessons.id = lessons_in_fields.lesson_id
WHERE lessons_in_fields.field_id IS NULL;
SQL;
    $lessonSQL = <<<SQL
SELECT DISTINCT lessons.id, lessons.name, UNIX_TIMESTAMP(lessons.version)
FROM lessons
JOIN lessons_in_fields ON lessons.id = lessons_in_fields.lesson_id
WHERE lessons_in_fields.field_id = :field_id;
SQL;

    $overrideGroup = (isset($data['override-group']) and $data['override-group'] == 'true');

    $fields = [new LessonContainer()];

    $db = new Database();
    $db->prepare($anonymousSQL);
    populateContainer($db, end($fields), $overrideGroup);

    // Select all the fields in the database
    $db->prepare($fieldSQL);
    $db->execute();
    $field_id = '';
    $field_name = '';
    $db->bindColumn('id', $field_id);
    $db->bindColumn('name', $field_name);

    while ($db->fetch()) {
        $fields[] = new Field($field_id, strval($field_name)); // Create a new field

        $db2 = new Database();
        $db2->prepare($lessonSQL);
        $db2->bindParam(':field_id', $field_id, PDO::PARAM_STR);
        populateContainer($db2, end($fields), $overrideGroup);

        // Sort the lessons in the newly-created Field - sorts by lowest competence low-to-high
        usort(end($fields)->lessons, 'Skaut\HandbookAPI\v0_9\Lesson_cmp');
    }
    usort($fields, 'Skaut\HandbookAPI\v0_9\LessonContainer_cmp'); // Sort all the Fields by their lowest competence
    return ['status' => 200, 'response' => $fields];
};
