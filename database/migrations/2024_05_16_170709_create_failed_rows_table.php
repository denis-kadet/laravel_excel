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
        Schema::create('failed_rows', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->unsignedBigInteger('row')->nullable();
            $table->string('message');
            $table->foreignId('task_id')->index()->constrained('tasks');
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
        Schema::dropIfExists('failed_rows');
    }
};
