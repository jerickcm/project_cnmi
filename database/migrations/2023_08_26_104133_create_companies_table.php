<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->mediumText('company_name', 150)->nullable();
            $table->mediumText('contact_address', 1000)->nullable();
            $table->mediumText('contact_number', 150)->nullable();
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
        Schema::dropIfExists('companies');
    }
}
