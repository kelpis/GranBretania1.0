<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // Safety check: abort if there are translation requests not linked to a user
        $orphanCount = DB::table('translation_requests')->whereNull('user_id')->count();
        if ($orphanCount > 0) {
            throw new \RuntimeException("Aborting migration: found {$orphanCount} translation_requests without user_id. Backfill or delete them before dropping legacy columns.");
        }

        Schema::table('translation_requests', function (Blueprint $table) {
            if (Schema::hasColumn('translation_requests', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('translation_requests', 'email')) {
                $table->dropColumn('email');
            }
        });
    }

    public function down()
    {
        Schema::table('translation_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('translation_requests', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (! Schema::hasColumn('translation_requests', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
        });
    }
};
