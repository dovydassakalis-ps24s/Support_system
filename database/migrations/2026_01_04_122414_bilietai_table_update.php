<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bilietai', function (Blueprint $table) {
            $table->string('statusas')->default('Laukiama')->after('aprasymas');
        });
    }

    public function down(): void
    {
        Schema::table('bilietai', function (Blueprint $table) {
            $table->dropColumn('statusas');
        });
    }
};
