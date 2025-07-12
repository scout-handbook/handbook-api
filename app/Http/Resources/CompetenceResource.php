<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $description
 * @property-read string $name
 * @property-read string $number
 */
final class CompetenceResource extends JsonResource
{
    public bool $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @return array{description: string, name: string, number: string}
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     *
     * @suppress PhanUnusedPublicFinalMethodParameter
     *
     * @SuppressWarnings("PHPMD.UnusedFormalParameter")
     */
    public function toArray(Request $request): array
    {
        return [
            'description' => $this->description,
            'name' => $this->name,
            'number' => $this->number,
        ];
    }
}
