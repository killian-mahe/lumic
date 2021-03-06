<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropIndex('links_name_user_id_index');

            $table->dropForeign('links_user_id_foreign');
            $table->dropColumn(['user_id']);

            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id')->references('id')->on('teams');

            $table->index(['name', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropIndex('links_name_team_id_index');

            $table->dropForeign('links_team_id_foreign');
            $table->dropColumn(['team_id']);

            $table->foreignId('user_id')->constrained();

            $table->index(['name', 'user_id']);
        });
    }
}
