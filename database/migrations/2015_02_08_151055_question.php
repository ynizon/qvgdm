<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Question extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('questions', function(Blueprint $table) {
			$table->string('id')->default("");
			$table->string('quizz_id')->default("");			
			$table->Integer('num')->unsigned()->default(0);
			$table->string('libelle')->default("");
			$table->string('background')->default("foucaut.jpg");
			$table->text('pj');
			$table->Integer('cacher_media')->unsigned()->default(0);
			
			$table->primary('id');
			$table->foreign('quizz_id')->references('id')
				->on('quizzs')->onDelete('cascade');  
		});
	  }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //		
		Schema::drop('questions'); 
    }
} 