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
        Schema::create('contractors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20);
            $table->string('timezone');
            $table->integer('max_delivery_radius');
            $table->foreignId('owned_by')->constrained('users');
            $table->foreignId('address_id')->constrained('addresses');
            $table->foreignId('email_id')->constrained('emails');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('contractors', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropForeign(['email_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['owned_by']);
        });

        Schema::dropIfExists('contractors');
    }
};
