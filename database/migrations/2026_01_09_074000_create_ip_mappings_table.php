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
        Schema::create('ip_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 15)->unique()->comment('IP address in 192.168.100.x format');
            $table->string('display_name')->comment('Custom name for the IP mapping');
            $table->boolean('is_active')->default(true)->comment('Enable/disable IP authentication');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->comment('Associated user account');
            $table->timestamp('last_used_at')->nullable()->comment('Last time this IP was used');
            $table->timestamps();

            $table->index('ip_address');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ip_mappings');
    }
};
