<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDynamicForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamic_forms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->tinyInteger('status')->comment('1: Active, 0 : Inactive')->default(1);
            $table->foreignId('created_by');
            $table->foreignId('modified_by');
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('table_dynamic_forms');
    }
}
