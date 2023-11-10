<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDolStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dol_staff', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('user_id');
            $table->unsignedMediumInteger('company_id');
            $table->mediumText('company_name', 150)->nullable();
            $table->mediumText('contact_address', 1000)->nullable();
            $table->mediumText('contact_number', 150)->nullable();
            $table->mediumText('business_id', 150)->nullable();
            $table->mediumText('business_type_id', 150)->nullable();
            $table->tinyInteger('verified')->default('0')->nullable();
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
        Schema::dropIfExists('dol_staff');
    }
}
