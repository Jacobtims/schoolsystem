<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Homework
 *
 * @property int $id
 * @property int $lesson_id
 * @property string $type
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Lesson $lesson
 * @method static \Illuminate\Database\Eloquent\Builder|Homework newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Homework newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Homework query()
 * @method static \Illuminate\Database\Eloquent\Builder|Homework whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homework whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homework whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homework whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homework whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Homework whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Homework extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'lesson_id',
        'type',
        'description'
    ];

    /**
     * @return BelongsTo
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
