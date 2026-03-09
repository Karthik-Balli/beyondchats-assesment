<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {

            $table->id();

            $table->foreignId('thread_id')->constrained();

            $table->string('gmail_message_id')->unique();

            $table->string('sender');
            $table->string('receiver');

            $table->longText('body_html')->nullable();

            $table->timestamp('sent_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
