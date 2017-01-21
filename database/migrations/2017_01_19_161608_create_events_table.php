<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('facebook_id')->nullable()->default(null)->unique();
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('place_id')->unsigned()->nullable();
            $table->string('name');
            $table->text('description')->nullable()->default(null);
            $table->dateTime('end_time')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->json('extra_info')->nullable();
            $table->timestamps();
    
            // Foreign keys
            $table->foreign('category_id')
                ->references('id')->on('event_categories');
    
            $table->foreign('place_id')
                ->references('id')->on('places');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
