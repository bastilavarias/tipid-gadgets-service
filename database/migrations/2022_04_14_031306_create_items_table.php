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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table
                ->string('slug')
                ->nullable()
                ->unique();
            $table->decimal('price', 9, 3);
            $table->text('description');
            $table->boolean('is_draft');
            $table->foreignId('item_section_id')->constrained('item_sections');
            $table->foreignId('item_category_id')->constrained('item_categories');
            $table->foreignId('item_condition_id')->constrained('item_conditions');
            $table->foreignId('item_warranty_id')->constrained('item_warranties');
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
        Schema::dropIfExists('items');
    }
};
