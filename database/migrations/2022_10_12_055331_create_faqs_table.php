<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id()->comment('Table PK');
            $table->string('question')->comment('Question');
            $table->text('answer')->comment('Answer');
            $table->enum('type',['general','pricing'])->default('general')->comment("'general'=> Faq for faqs page, 'pricing'=> Fqa for pricing page");
            $table->rememberToken();
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
        Schema::dropIfExists('faqs');
    }
};
