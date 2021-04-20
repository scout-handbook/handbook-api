<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

use Skaut\HandbookAPI\v0_9\Helper;

class Lesson implements \JsonSerializable
{
    private $id;
    private $name;
    private $version;
    private $competences;
    private $lowestCompetence;

    public function __construct(string $id, string $name, float $version)
    {
        $this->id = Uuid::fromBytes($id);
        $this->name = Helper::xssSanitize($name);
        $this->version = round($version * 1000);
        $this->competences = [];
        $this->lowestCompetence = 0;
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function setLowestCompetence(int $competence) : void
    {
        $this->lowestCompetence = $competence;
    }

    public function addCompetence(string $competence) : void
    {
        $this->competences[] = Uuid::fromBytes($competence);
    }

    public function jsonSerialize() : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'version' => $this->version,
            'competences' => $this->competences
        ];
    }

    // Lesson comparison function used in usort.
    // Assumes that both Lessons have their competences field sorted low-to-high.
    public static function compare(Lesson $first, Lesson $second) : int
    {
        if (empty($first->competences)) {
            if (empty($second->competences)) {
                return 0;
            }
            return -1;
        }
        if (empty($second->competences)) {
            return 1;
        }
        if ($first->lowestCompetence === $second->lowestCompetence) {
            return 0;
        }
        return ($first->lowestCompetence < $second->lowestCompetence) ? -1 : 1;
    }
}
