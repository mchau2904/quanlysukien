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
        Schema::create('feedback_replies', function (Blueprint $table) {
            $table->increments('reply_id');
            $table->unsignedInteger('feedback_id');   // liên kết tới feedback.feedback_id
            $table->unsignedInteger('sender_id');     // người gửi (GV hoặc SV)
            $table->unsignedInteger('receiver_id');   // người nhận
            $table->text('content');
            $table->timestamp('created_at')->useCurrent();

            // === FOREIGN KEYS ===
            $table->foreign('feedback_id')
                ->references('feedback_id')
                ->on('feedbacks')
                ->onDelete('cascade');

            $table->foreign('sender_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('receiver_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_replies');
    }
};
