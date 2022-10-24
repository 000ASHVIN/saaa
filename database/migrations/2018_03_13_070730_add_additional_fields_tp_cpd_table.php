<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFieldsTpCpdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cpds', function (Blueprint $table) {
            $table->boolean('verifiable')->default(false);
            $table->enum('category',
                [
                    'tax',
                    'ethics',
                    'auditing',
                    'verifiable',
                    'accounting',
                    'unstructed',
                    'non_verifiable'
                ])->default('non_verifiable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cpds', function (Blueprint $table) {
            $table->dropColumn(['verifiable', 'category']);
        });
    }
}
