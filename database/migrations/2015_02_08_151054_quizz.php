<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Quizz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('quizzs', function(Blueprint $table) {
			$table->string('id')->default("");
			$table->bigInteger('user_id')->unsigned();			
			$table->string('id_quizz')->default("");
			$table->string('type')->default("");
			$table->string('nom')->default("");
			$table->string('pass_quizz')->default("");
			$table->text('intro');
			$table->text('conclusion');
			$table->Integer('nb')->unsigned()->default(0);
			$table->Integer('nbvotes')->unsigned()->default(0);
			$table->Integer('nbgagner')->unsigned()->default(0);
			$table->Integer('status')->unsigned()->default(0);
			$table->string('langue')->default("fr");
			$table->timestamps();			
			
			$table->primary('id');
			$table->foreign('user_id')->references('id')
				->on('users')->onDelete('cascade');  
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
		Schema::drop('quizzs'); 
    }
} 