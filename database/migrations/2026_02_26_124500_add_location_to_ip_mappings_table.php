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
        if (!Schema::hasColumn('ip_mappings', 'location')) {
            Schema::table('ip_mappings', function (Blueprint $table) {
                $table->string('location')->nullable()->after('display_name')->comment('Physical location of the device');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ip_mappings', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
};
