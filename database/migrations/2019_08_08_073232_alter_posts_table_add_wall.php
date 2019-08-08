<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPostsTableAddWall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'wall_id')) {
                $table->string('wall_id')->default('0')->after('user_id');
            }

            if (!Schema::hasColumn('posts', 'wall_type')) {
                $table->string('wall_type')->nullable()->after('wall_id');
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
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'wall_id')) {
                $table->dropColumn('wall_id');
            }

            if (Schema::hasColumn('posts', 'wall_type')) {
                $table->dropColumn('wall_type');
            }
        });
    }
}
