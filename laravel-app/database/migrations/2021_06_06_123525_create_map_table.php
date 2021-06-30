<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maps_map', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('abstract')->nullable();
            $table->integer('zoom')->nullable();
            $table->float('center_x')->nullable();
            $table->float('center_y')->nullable();
            $table->string('projection', 32)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('maps_maplayer', function (Blueprint $table) {
            $table->id();
            $table->integer('map_id')->nullable();
            $table->integer('stack_order')->nullable();
            $table->string('format', 200)->nullable();
            $table->string('name', 200)->nullable();
            $table->float('opacity')->nullable();
            $table->string('styles', 200)->nullable();
            $table->boolean('transparent')->nullable();
            $table->string('group')->nullable();
            $table->boolean('visibility')->nullable();
            $table->string('url')->nullable();
            $table->boolean('local')->nullable();
            $table->json('params')->nullable();
            $table->json('data')->nullable();
            $table->json('options')->nullable();
            $table->json('popup')->nullable();
            $table->timestamps();
        });

        Schema::create('services_service', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('method');
            $table->string('base_url');
            $table->string('version');
            $table->string('name');
            $table->string('description');
            $table->string('username');
            $table->string('password');
            $table->string('api_key');
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
        Schema::dropIfExists('maps_map');
        Schema::dropIfExists('maps_maplayer');
        Schema::dropIfExists('services_service');
    }
}
