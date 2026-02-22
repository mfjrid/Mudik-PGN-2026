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
        Schema::table('buses', function (Blueprint $table) {
            $table->string('layout_type')->default('2-2')->after('capacity');
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->integer('row')->after('seat_number')->nullable();
            $table->integer('column')->after('row')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn('layout_type');
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->dropColumn(['row', 'column']);
        });
    }
};
