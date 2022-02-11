<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDynamicFormFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamic_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id');
            $table->string('label')->nullable();
            $table->string('type')->nullable();
            $table->integer('is_required')->nullable();
            $table->integer('sort_order')->nullable();
            $table->tinyInteger('status')->comment('1: Active, 0 : Inactive')->default(1);
            $table->foreignId('created_by');
            $table->foreignId('modified_by');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('dynamic_forms')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_dynamic_form_fields');
    }
}
