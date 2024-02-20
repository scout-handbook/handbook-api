<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;

use Skaut\HandbookAPI\v1_0\Helper;

class Lesson implements \JsonSerializable
{
    private $name;
    private $version;
    private $competences;

    public function __construct(string $name, float $version)
    {
        $this->name = Helper::xssSanitize($name);
        $this->version = round($version * 1000);
        $this->competences = [];
    }

    public function addCompetence(string $competence): void
    {
        $this->competences[] = Uuid::fromBytes($competence);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCompetences(): array
    {
        return $this->competences;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'version' => $this->version,
            'competences' => $this->competences
        ];
    }
}
