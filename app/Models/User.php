<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Support\Facades\Log;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Crypt;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'username',
        'sex',
        'user_type',
        'email',
        'email_verified_at',
        'password',
        'generated_password',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'staff_role' => 'integer',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',


    ];

    protected $hidden = [
        'password',
        'generated_password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'name',
    ];

    //    public static function where(string $string, string $string1) {}

    public function profilePhotos() {}

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    // Accessor for full name
    public function getNameAttribute(): string
    {
        try {
            // Decrypt the names
            $firstName = Crypt::decryptString($this->first_name);
            $lastName = Crypt::decryptString($this->last_name);

            // Handle middle name if it exists
            $middleInitial = '';
            if (!empty($this->middle_name)) {
                $decryptedMiddleName = Crypt::decryptString($this->middle_name);
                $middleInitial = !empty($decryptedMiddleName) ? $decryptedMiddleName[0] . '.' : '';
            }

            return "$firstName $middleInitial $lastName";
        } catch (\Exception $e) {
            // Fallback in case decryption fails
            Log::error('Error decrypting user name: ' . $e->getMessage());
            return "User"; // Return a generic name as fallback
        }
    }

    public function getUserTypeNameAttribute(): string
    {
        // Check if the user has an associated userType, otherwise return 'Unknown'
        return $this->userTypes->type;
    }

    // Custom accessor for profile picture URL
    public function getProfilePictureUrlAttribute(): string
    {
        if ($this->profilePhoto && $this->profilePhoto->photo_path) {
            return asset('storage/' . $this->profilePhoto->photo_path);
        }

        // Check the gender and return the appropriate default image
        try {
            $decryptedSex = Crypt::decryptString($this->sex);
        } catch (\Exception $e) {
            Log::error('Error decrypting user sex: ' . $e->getMessage());
            $decryptedSex = 'male'; // fallback default
        }

        return $decryptedSex === 'female'
            ? asset('storage/icons/profile2-graphics.png')
            : asset('storage/icons/profile-graphics.png');
    }


    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    public function resident(): HasOne
    {
        return $this->hasOne(Resident::class);
    }

    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    public function adminLogs(): HasMany
    {
        return $this->hasMany(AdminLog::class);
    }

    public function staffLogs(): HasMany
    {
        return $this->hasMany(StaffLog::class);
    }

    public function residentLogs(): HasMany
    {
        return $this->hasMany(ResidentLog::class);
    }


    public function userTypes(): BelongsTo
    {
        return $this->belongsTo(UserType::class, 'user_type');
    }

    public function staffRolesPermissions(): BelongsTo
    {
        return $this->belongsTo(StaffRolesPermissions::class, 'staff_roles_permission_id');
    }


    //    public function staffRole(): BelongsTo
    //    {
    //        return $this->belongsTo(StaffRole::class, 'staff_role_id');
    //    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(StaffPermission::class, 'staff_roles_permissions');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(StaffRole::class, 'staff_role_id');
    }


    public function profilePhoto(): HasOne
    {
        return $this->hasOne(UserProfilePhoto::class);
    }

    public function notifications(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }


    //    public function admin_notifications(): HasMany
    //    {
    //        return $this->hasMany(Notification::class, 'admin_user_id', 'id');
    //    }

}
