<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('action');
            $table
                ->foreignId('item_id')
                ->nullable()
                ->constrained('items');
            $table
                ->foreignId('topic_id')
                ->nullable()
                ->constrained('topics');
            $table
                ->foreignId('comment_id')
                ->nullable()
                ->constrained('topic_comments');
            $table->boolean('is_read')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
