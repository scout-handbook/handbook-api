<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Competence;
use App\Http\Resources\CompetenceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CompetenceController extends Controller
{
    public static function index(): CompetenceCollection
    {
        return new CompetenceCollection(Competence::all());
    }

    public static function store(Request $request): JsonResponse
    {
        $competence = new Competence;

        $competence->description = $request->description ?? '';
        $competence->name = $request->name;
        $competence->number = $request->number;

        $competence->save();

        return new JsonResponse(['status' => 201], 201);
    }

    /*
    public static function update(Request $request, Competence $competence)
    {
        // TODO
    }

    public static function destroy(Competence $competence)
    {
        // TODO
    }
    */
}
