<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained();
            $table->foreignId('teacher_id')->nullable()->constrained();
            $table->foreignId('school_class_id')->nullable()->constrained();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('from')->constrained('standard_lessons', 'lesson');
            $table->foreignId('to')->constrained('standard_lessons', 'lesson');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
