<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Lesson
 *
 * @property int $id
 * @property int $school_class_id
 * @property int $teacher_id
 * @property int $subject_id
 * @property string $date
 * @property \App\Models\StandardLesson $time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SchoolClass $schoolClass
 * @property-read \App\Models\Subject $subject
 * @property-read \App\Models\Teacher $teacher
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereSchoolClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $deleted
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereDeleted($value)
 * @property int|null $attendance_registered
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attendance[] $attendances
 * @property-read int|null $attendances_count
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereAttendanceRegistered($value)
 * @property int $classroom_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attendance[] $absentees
 * @property-read int|null $absentees_count
 * @property-read \App\Models\Classroom $classroom
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereClassroomId($value)
 */
class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'school_class_id',
        'teacher_id',
        'subject_id',
        'date',
        'time',
        'attendance_registered',
        'deleted',
        'class_room_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'attendance_registered'
    ];

    /**
     * @return BelongsTo
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * @return BelongsTo
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    /**
     * @return BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * @return BelongsTo
     */
    public function time(): BelongsTo
    {
        return $this->belongsTo(StandardLesson::class, 'time', 'id');
    }

    /**
     * @return HasMany
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * @return HasMany
     */
    public function absentees(): HasMany
    {
        return $this->hasMany(Attendance::class)->where('present', false);
    }

    /**
     * @return BelongsTo
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
