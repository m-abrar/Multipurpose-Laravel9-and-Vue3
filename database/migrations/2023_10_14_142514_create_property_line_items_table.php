<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_line_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('icon', 25)->nullable();
            $table->string('image', 255)->nullable();
            $table->float('value')->default(0);
            $table->string('value_type', 25)->nullable();
            $table->string('apply_on', 25)->nullable();
            $table->boolean('is_required')->nullable();
            $table->integer('diplay_order')->nullable();
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_line_items');
    }
};
