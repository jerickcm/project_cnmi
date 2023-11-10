<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkforceListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workforce_listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('company_id');
            $table->unsignedMediumInteger('document_id');
            $table->unsignedMediumInteger('employer_id');
     
            $table->mediumText('full_name',200)->nullable();
            $table->mediumText('first_name',200)->nullable();
            $table->mediumText('middle_name',200)->nullable();
            $table->mediumText('last_name',200)->nullable();
            $table->mediumText('major_soc_code',2)->nullable();
            $table->mediumText('minor_soc_code',2)->nullable();
            $table->mediumText('position',200)->nullable();
            $table->unsignedTinyInteger('project_exemption')->default(null)->nullable();
            $table->mediumText('employment_status')->default(null)->nullable();
            $table->mediumText('wage')->default(null)->nullable();
            $table->mediumText('country_of_citizenship',15)->default(null)->nullable();
            $table->mediumText('visa_type_class',15)->default(null)->nullable();
            $table->date('employment_start_date')->default(null)->nullable();
            $table->date('employment_end_date')->default(null)->nullable();
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
        Schema::dropIfExists('workforce_listings');
    }
}
