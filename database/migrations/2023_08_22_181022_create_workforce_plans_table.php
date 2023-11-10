<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkforcePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workforce_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('document_id');
            $table->unsignedMediumInteger('company_id');
            $table->unsignedMediumInteger('employer_id');

            $table->mediumText('full_name',200)->nullable();
            $table->mediumText('first_name',200)->nullable();
            $table->mediumText('middle_name',200)->nullable();
            $table->mediumText('last_name',200)->nullable();
            $table->mediumText('employment',200)->nullable();
            $table->mediumText('visa_expiration_date',200)->nullable();
            $table->mediumText('occupational_classification_code')->nullable();
            $table->mediumText('timetable_replacement_foreignworkers')->nullable();
            $table->mediumText('specific_replacement_plan')->nullable();
            $table->unsignedTinyInteger('status')->default(0)->nullable();
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
        Schema::dropIfExists('workforce_plans');
    }
}
