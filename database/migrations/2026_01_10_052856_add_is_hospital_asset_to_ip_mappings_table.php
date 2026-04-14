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
        Schema::table('ip_mappings', function (Blueprint $table) {
            $table->boolean('is_hospital_asset')->default(false)->after('is_active')->comment('Mark if this IP belongs to a hospital asset');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ip_mappings', function (Blueprint $table) {
            $table->dropColumn('is_hospital_asset');
        });
    }
};
