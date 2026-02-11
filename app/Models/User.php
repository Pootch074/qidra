<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    const TYPE_SUPERADMIN = 0;

    const TYPE_ADMIN = 1;

    const TYPE_IDSCAN = 2;

    const TYPE_PACD = 3;

    const TYPE_USER = 5;

    const TYPE_DISPLAY = 6;

    const STATUS_INACTIVE = 0;

    const STATUS_ACTIVE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'division_id',
        'section_id',
        'first_name',
        'last_name',
        'email',
        'position',
        'user_type',
        'category_id',
        'step_id',
        'window_id',
        'status',
        'email_is_verified',
        'otp_code',
        'otp_expires_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = ['deleted_at']; // Ensure Laravel treats it as a date

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'string',
            'category_id' => 'integer',
            'window_id' => 'integer',
            'status' => 'integer',
            'user_type' => 'integer',
            'otp_expires_at' => 'datetime', // <-- add this line
            'email_is_verified' => 'boolean',
        ];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ?? now();
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value ?? 0;
    }

    public static function getUserTypes(): array
    {
        return [
            self::TYPE_SUPERADMIN => 'Super Admin',
            self::TYPE_ADMIN => 'Admin',
            self::TYPE_USER => 'User',
            self::TYPE_PACD => 'PACD',
            self::TYPE_DISPLAY => 'Display',
            self::TYPE_IDSCAN => 'Idscan',
        ];
    }

    public function getUserTypeTextAttribute(): string
    {
        return self::getUserTypes()[$this->user_type] ?? 'Unknown';
    }

    public static function getAssignableUserTypes(): array
    {
        return [
            self::TYPE_ADMIN => 'Admin',
            self::TYPE_USER => 'User',
            self::TYPE_PACD => 'PACD',
            self::TYPE_DISPLAY => 'Display',
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_ACTIVE => 'Active',
        ];
    }

    public function getStatusTextAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? 'Unknown';
    }

    public function scopeAdmins($query)
    {
        return $query->where('user_type', self::TYPE_ADMIN);
    }

    public function scopeSuperAdmins($query)
    {
        return $query->where('user_type', self::TYPE_SUPERADMIN);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeUsers($query)
    {
        return $query->where('user_type', self::TYPE_USER);
    }

    public function scopePacds($query)
    {
        return $query->where('user_type', self::TYPE_PACD);
    }

    public function scopeDisplays($query)
    {
        return $query->where('user_type', self::TYPE_DISPLAY);
    }

    public function scopeIdscans($query)
    {
        return $query->where('user_type', self::TYPE_IDSCAN);
    }
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function step()
    {
        return $this->belongsTo(Step::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function window()
    {
        return $this->belongsTo(Window::class);
    }

    public function isAdmin(): bool
    {
        return $this->user_type === self::TYPE_ADMIN;
    }
}
