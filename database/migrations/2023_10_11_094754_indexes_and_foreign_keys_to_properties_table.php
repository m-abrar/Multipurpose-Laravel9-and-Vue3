<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IndexesAndForeignKeysToPropertiesV2 extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            // Add Indexes
            $table->index(['name', 'slug', 'property_type_id', 'location_id', 'user_id']);

            // Add Foreign Keys
            $table->foreign('property_type_id')->references('id')->on('property_types');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            // Remove Indexes
            $table->dropIndex(['name', 'slug', 'property_type_id', 'location_id', 'user_id']);

            // Remove Foreign Keys
            $table->dropForeign(['property_type_id']);
            $table->dropForeign(['location_id']);
            $table->dropForeign(['user_id']);
        });
    }
}
