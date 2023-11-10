<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('company_id')->nullable();
            $table->unsignedMediumInteger('business_id')->nullable();
            $table->unsignedMediumInteger('business_type_id')->nullable();
            $table->date('create_date')->nullable();
            $table->date('registration_date')->nullable();
            $table->unsignedtinyInteger('status')->default(0)->nullable();
            $table->unsignedtinyInteger('verified')->default(0)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('categories');
    }
}
