<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSupportTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_account_id');
            $table->foreign('business_account_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('ticket_number');
            $table->text('subject');
            $table->text('message');
            $table->string('attachment')->nullable();
            $table->string('contact_no')->nullable();
            $table->enum('severity', ['1', '2', '3'])->comment('1 => High, 2 => Medium , 3 => Low');
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
        Schema::dropIfExists('support_tickets');
    }
}
