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
        Schema::table('assets', function (Blueprint $table) {
            if (!Schema::hasColumn('assets', 'remote_app_name')) {
                $table->string('remote_app_name')->nullable()->after('status')->comment('e.g., AnyDesk, TeamViewer');
            }
            if (!Schema::hasColumn('assets', 'remote_address')) {
                $table->string('remote_address')->nullable()->after('remote_app_name')->comment('ID or IP Address');
            }
            if (!Schema::hasColumn('assets', 'remote_password')) {
                $table->string('remote_password')->nullable()->after('remote_address');
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
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['remote_app_name', 'remote_address', 'remote_password']);
        });
    }
};
