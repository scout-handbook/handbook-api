<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Competence;
use App\Http\Resources\CompetenceCollection;

final class CompetenceController extends Controller
{
    public static function index(): CompetenceCollection
    {
        return new CompetenceCollection(Competence::all());
    }

    /*
    public static function store(Request $request)
    {
        // TODO
    }

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
