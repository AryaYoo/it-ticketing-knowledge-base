<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['computer', 'non-computer'])->default('non-computer');
            $table->string('name')->nullable()->comment('Name for non-computer assets');
            $table->foreignId('ip_mapping_id')->nullable()->constrained()->onDelete('set null')->comment('Reference for computer assets');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->default('active')->comment('active, broken, disposed');
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
        Schema::dropIfExists('assets');
    }
};
