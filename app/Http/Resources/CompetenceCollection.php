<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class CompetenceCollection extends ResourceCollection
{
    /**
     * @return array{
     *     response: \Illuminate\Support\Collection<string, array{description: string, name: string, number: string}>,
     *     status: 200
     * }
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
            'response' => $this->collection->keyBy->id,
            'status' => 200,
        ];
    }
}
