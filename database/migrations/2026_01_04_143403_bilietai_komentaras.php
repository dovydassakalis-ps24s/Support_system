<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bilietai', function (Blueprint $table) {
            $table->string('komentaras')->default(' ')->after('statusas');
        });
    }

    public function down(): void
    {
        Schema::table('bilietai', function (Blueprint $table) {
            $table->dropColumn('komentaras');
        });
    }
};
