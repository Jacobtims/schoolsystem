<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StandardLesson
 *
 * @property int $lesson
 * @property string $from
 * @property string $to
 * @method static \Illuminate\Database\Eloquent\Builder|StandardLesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StandardLesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StandardLesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|StandardLesson whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StandardLesson whereLesson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StandardLesson whereTo($value)
 * @mixin \Eloquent
 * @property int $day
 * @method static \Illuminate\Database\Eloquent\Builder|StandardLesson whereDay($value)
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|StandardLesson whereId($value)
 */
class StandardLesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'from',
        'to'
    ];
}
