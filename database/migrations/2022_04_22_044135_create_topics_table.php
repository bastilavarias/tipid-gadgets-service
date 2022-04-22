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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table
                ->string('slug')
                ->nullable()
                ->unique();
            $table->boolean('is_draft');
            $table->foreignId('user_id')->constrained('users');
            $table
                ->foreignId('topic_description_id')
                ->nullable()
                ->constrained('topic_descriptions');
            $table
                ->foreignId('topic_section_id')
                ->nullable()
                ->constrained('topic_sections');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }
};
