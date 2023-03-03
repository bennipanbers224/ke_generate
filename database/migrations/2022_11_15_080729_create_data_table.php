<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("certificate_number");
            $table->string("major");
            $table->string("title");
            $table->string("predicate");
            $table->string("graduation_date");
            $table->string("start_study");
            $table->string("nim");
            $table->string("image");
            $table->string("private_key");
            $table->string("public_key");
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
        Schema::dropIfExists('data');
    }
}
