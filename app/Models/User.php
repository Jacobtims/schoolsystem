<?php

namespace App\Models;

// TODO: use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'firstname',
        'lastname',
        'phone_number',
        'date_of_birth',
        'country',
        'state',
        'city',
        'zipcode',
        'street',
        'role_id',
        'sex'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role_id === Role::whereName('admin')->firstOrFail()->id;
    }

    /**
     * @return bool
     */
    public function getIsTeacherAttribute(): bool
    {
        return $this->role_id === Role::whereName('teacher')->firstOrFail()->id;
    }

    /**
     * @return bool
     */
    public function getIsStudentAttribute(): bool
    {
        return $this->role_id === Role::whereName('student')->firstOrFail()->id;
    }

    /**
     * @return HasManyThrough
     */
    public function permissions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Permission::class,
            Role::class,
            'id', // Foreign key on the roles table...
            'id', // Foreign key on the permissions table...
            'id', // Local key on the users table...
            'id' // Local key on the roles table...
        );
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        if (!($permission instanceof Permission)) {
            $permission = Permission::whereName($permission)->first();
        }
        if ($this->permissions->contains($permission)) {
            return true;
        }
        return false;
    }

    /**
     * @return HasOne
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /**
     * @return HasOne
     */
    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }
}
