<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perpus_users', function (Blueprint $table) {
            $table->string('jurusan')->nullable()->after('kelas');
        });
    }

    public function down(): void
    {
        Schema::table('perpus_users', function (Blueprint $table) {
            $table->dropColumn('jurusan');
        });
    }
};
