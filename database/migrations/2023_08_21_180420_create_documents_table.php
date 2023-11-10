<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('company_id');
            $table->unsignedMediumInteger('employer_id');
            $table->mediumText('type', 150)->nullable();
            $table->string('orig_title')->nullable();
            $table->unsignedTinyInteger('business_industry_id')->nullable();
            $table->unsignedTinyInteger('business_type_id')->nullable();
            $table->string('title')->nullable();
            $table->string('file')->nullable();
            $table->string('notes')->nullable();
            $table->year('year')->nullable();
            $table->unsignedTinyInteger('quarter')->default(null)->nullable();
            $table->unsignedTinyInteger('approved')->default(0)->nullable();
            $table->date('registration_date')->nullable();
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
        Schema::dropIfExists('documents');
    }
}
