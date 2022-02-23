<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AttendanceType
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceType query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceType whereName($value)
 * @mixin \Eloquent
 */
class AttendanceType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name'
    ];
}
