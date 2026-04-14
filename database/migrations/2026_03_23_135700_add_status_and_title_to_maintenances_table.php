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
        Schema::table('maintenances', function (Blueprint $table) {
            if (!Schema::hasColumn('maintenances', 'title')) {
                $table->string('title')->nullable()->after('asset_id');
            }
            if (!Schema::hasColumn('maintenances', 'status')) {
                $table->enum('status', ['pending', 'completed'])->default('completed')->after('cost');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn(['title', 'status']);
        });
    }
};
