<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Lesson;

$listLessons = function (Skautis $skautis, array $data) : array {
    $lessonSQL = <<<SQL
SELECT id, name, UNIX_TIMESTAMP(version)
FROM lessons;
SQL;
    $competenceSQL = <<<SQL
SELECT competence_id
FROM competences_for_lessons
WHERE lesson_id = :lesson_id
SQL;

    $overrideGroup = (isset($data['override-group']) and $data['override-group'] == 'true');

    $lessons = [];

    $db = new Database();
    $db->prepare($lessonSQL);
    $db->execute();
    $lesson_id = '';
    $lesson_name = '';
    $lesson_version = '';
    $db->bindColumn('id', $lesson_id);
    $db->bindColumn('name', $lesson_name);
    $db->bindColumn(3, $lesson_version);

    while ($db->fetch()) {
        if (!checkLessonGroup(Uuid::fromBytes($lesson_id), $overrideGroup)) {
            continue;
        }
        $newLesson = new Lesson($lesson_name, floatval($lesson_version));

        $db2 = new Database();
        $db2->prepare($competenceSQL);
        $db2->bindParam(':lesson_id', $lesson_id, PDO::PARAM_STR);
        $db2->execute();
        $competence_id = '';
        $db2->bindColumn('competence_id', $competence_id);
        while ($db2->fetch()) {
            $newLesson->addCompetence($competence_id);
        }

        $lessons[Uuid::fromBytes($lesson_id)->toString()] = $newLesson;
    }
    return ['status' => 200, 'response' => $lessons];
};
