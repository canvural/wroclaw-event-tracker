<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('facebook_id')->nullable()->default(null);
            $table->integer('category_id')->unsigned();
            $table->string('name');
            $table->text('description')->nullable()->default(null);
            $table->mediumText('short_description')->nullable()->default(null);
            $table->json('location');
            $table->float('rating')->default(0.0);
            $table->string('phone')->nullable()->default(null);
            $table->string('website')->nullable()->default(null);
            $table->json('extra_info')->nullable();
            $table->timestamps();
    
            // Foreign keys
            $table->foreign('category_id')
                ->references('id')->on('place_categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
