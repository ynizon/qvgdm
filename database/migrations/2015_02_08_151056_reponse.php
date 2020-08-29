<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Reponse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('reponses', function(Blueprint $table) {
			$table->string('id')->default("");
			$table->string('question_id')->default("");			
			$table->Integer('num')->unsigned()->default(0);
			$table->Integer('valide')->unsigned()->default(0);
			$table->Integer('vote')->unsigned()->default(25);
			$table->string('libelle')->default("");			
			
			$table->primary('id');
			$table->foreign('question_id')->references('id')
				->on('questions')->onDelete('cascade');  
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
		Schema::drop('reponses'); 
    }
} 