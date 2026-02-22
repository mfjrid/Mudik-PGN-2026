<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable();
        });

        // Populate UUIDs for existing records
        $registrations = DB::table('registrations')->get();
        foreach ($registrations as $reg) {
            DB::table('registrations')
                ->where('id', $reg->id)
                ->update(['uuid' => (string) \Illuminate\Support\Str::uuid()]);
        }

        Schema::table('registrations', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
