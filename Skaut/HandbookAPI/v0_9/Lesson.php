<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;

use Skaut\HandbookAPI\v0_9\Helper;

class Lesson implements \JsonSerializable
{
    public $id;
    public $name;
    public $version;
    public $competences = [];
    public $lowestCompetence;

    public function __construct(string $id, string $name, float $version)
    {
        $this->id = Uuid::fromBytes($id);
        $this->name = Helper::xssSanitize($name);
        $this->version = round($version * 1000);
    }

    public function jsonSerialize() : array
    {
        $ucomp = [];
        foreach ($this->competences as $competence) {
            $ucomp[] = Uuid::fromBytes($competence);
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'version' => $this->version,
            'competences' => $ucomp
        ];
    }
}
