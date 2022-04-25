<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
            $table->dropColumn('description');
            $table->dropColumn('weighting');

            $table->foreignId('assignment_id')->after('id')->constrained();
            $table->unique(['assignment_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropUnique(['assignment_id', 'student_id']);
            $table->foreignId('subject_id')->after('teacher_id')->constrained();
            $table->string('description')->after('number');
            $table->float('weighting')->after('description');
        });
    }
}
