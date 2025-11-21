<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('target_faculty', 100)->nullable()->after('location'); // Khoa áp dụng
            $table->string('target_class', 100)->nullable()->after('target_faculty'); // Lớp áp dụng
            $table->enum('target_gender', ['Nam', 'Nữ', 'Tất cả'])->default('Tất cả')->after('target_class'); // Giới tính áp dụng
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['target_faculty', 'target_class', 'target_gender']);
        });
    }

};
