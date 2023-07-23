<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->string('blog_id')->nullable();
            $table->string('widget_position')->default('left');
            $table->string('widget_url')->nullable();
            $table->boolean('is_enabled')->default(1);
            $table->string('api_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('blog_id');
            $table->dropColumn('widget_position');
            $table->dropColumn('widget_url');
            $table->dropColumn('is_enabled');
            $table->dropColumn('api_token');
        });
    }
}
