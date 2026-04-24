<?php

use App\Enums\Priority;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Set all existing tasks to High as a sensible default
        DB::table('tasks')->update(['priority' => Priority::High->value]);

        Schema::table('tasks', function (Blueprint $table) {
            // Add a standalone index on project_id so the FK constraint remains
            // satisfied, then drop the old composite (project_id, priority) index.
            $table->index('project_id');
            $table->dropIndex(['project_id', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->index(['project_id', 'priority']);
        });
    }
};
