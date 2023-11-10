<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('user_id');
            $table->unsignedMediumInteger('company_id');
            $table->mediumText('company_name', 150)->nullable();
            $table->mediumText('contact_address', 1000)->nullable();
            $table->mediumText('contact_number', 150)->nullable();
            // $table->unsignedMediumInteger('businesses_id')->default(null)->nullable();
            // $table->unsignedMediumInteger('business_types_id')->default(null)->nullable();
            $table->tinyInteger('verified')->default(0)->nullable();
            $table->tinyInteger('checkbox_year')->default(0)->nullable();
            $table->tinyInteger('checkbox_quarter')->default(0)->nullable();
            $table->year('year')->default(null)->nullable();
            $table->string('quarter')->default(null)->nullable();
            $table->date('create_date')->nullable();
            $table->date('registration_date')->nullable();
            $table->unsignedtinyInteger('status')->default(0)->nullable();
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
        Schema::dropIfExists('employers');
    }
}
