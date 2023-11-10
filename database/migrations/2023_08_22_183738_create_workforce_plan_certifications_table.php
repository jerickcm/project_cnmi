<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkforcePlanCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('workforce_plan_certifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('company_id');
            $table->unsignedMediumInteger('document_id');
            $table->unsignedMediumInteger('employer_id');
            $table->mediumText('name_and_position')->default(null)->nullable();
            $table->mediumText('file_id',200)->nullable();
            $table->mediumText('company_name')->default(null)->nullable();
            $table->mediumText('dba')->default(null)->nullable();
            $table->mediumText('day')->default(null)->nullable();
            $table->mediumText('month')->default(null)->nullable();
            $table->mediumText('year')->default(null)->nullable();
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
        Schema::dropIfExists('workforce_plan_certifications');
    }
}
