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
        Schema::table('tickets', function (Blueprint $table) {
            $table->timestamp('resolved_at')->nullable()->after('status');
            $table->string('client_image_path')->nullable()->after('description');
            $table->text('resolution_problem_summary')->nullable()->after('resolved_at');
            $table->text('resolution_steps')->nullable()->after('resolution_problem_summary');
            $table->string('resolution_image_path')->nullable()->after('resolution_steps');
        });

        // Modifying ENUM can be tricky without doctrine/dbal, so we use a raw statement for MariaDB/MySQL
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('open', 'in_progress', 'resolved', 'closed', 'escalated') DEFAULT 'open'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'resolved_at',
                'client_image_path',
                'resolution_problem_summary',
                'resolution_steps',
                'resolution_image_path',
            ]);
        });

        // Revert enum
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open'");
    }
};
