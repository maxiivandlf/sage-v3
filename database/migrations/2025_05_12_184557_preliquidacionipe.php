<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Preliquidacionipe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_agentesCobroIpe', function (Blueprint $table) {
            $table->id();
            $table->string('docu');
            $table->string('cuil');
            $table->string('trab');
            $table->string('nomb');
            $table->string('sexo');
            $table->string('zona');
            $table->string('escu');
            $table->string('plan');
            $table->string('lcat');
            $table->string('ncat');
            $table->string('hora');
            $table->string('agru');
            $table->string('area');
            $table->string('dias');
            $table->string('IPE');
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
        Schema::dropIfExists('tb_agentesCobroIpe');
    }
}
