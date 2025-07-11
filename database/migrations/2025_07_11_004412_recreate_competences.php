<?php

declare(strict_types=1);

use App\Competence;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    public static function up(): void
    {
        $hasOldTable = Schema::hasTable('competences');

        if ($hasOldTable) {
            Schema::rename('competences', 'legacy_competences');
        }

        Schema::create('competences', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('number', 15);
            $table->string('name');
            $table->text('description');
        });

        if (! $hasOldTable) {
            return;
        }

        $competences = DB::table('legacy_competences')->get();

        foreach ($competences as $oldCompetence) {
            $newCompetence = new Competence;
            $newCompetence->id = Uuid::fromBytes($oldCompetence->id)->toString();
            $newCompetence->number = $oldCompetence->number;
            $newCompetence->name = $oldCompetence->name;
            $newCompetence->description = $oldCompetence->description;
            $newCompetence->save();
        }

        Schema::drop('legacy_competences');
    }
};
