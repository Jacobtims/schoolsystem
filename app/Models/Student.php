<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Student
 *
 * @property int $id
 * @property int $user_id
 * @property int $school_class_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attendance[] $attendances
 * @property-read int|null $attendances_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Grade[] $grades
 * @property-read int|null $grades_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lesson[] $lessons
 * @property-read int|null $lessons_count
 * @property-read \App\Models\SchoolClass $schoolClass
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\StudentFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereSchoolClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereUserId($value)
 * @mixin \Eloquent
 */
class Student extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'school_class_id'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * @return BelongsTo
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    /**
     * @return HasManyThrough
     */
    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(
            Lesson::class,
            SchoolClass::class,
            'id', // Foreign key on the school_classes table...
            null, // Foreign key on the lessons table...
            'school_class_id', // Local key on the this (student) table...
            null // Local key on the school_classes table...
        );
    }

    /**
     * @return HasMany
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
