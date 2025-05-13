<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_role',
        // 'generated password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $table = 'staffs';

    // Attributes
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'staff_roles_permissions_id' => 'integer',
    ];

    //Relationships
    public function staffRolesPermissions(): BelongsTo
    {
        return $this->belongsTo(StaffRolesPermissions::class, 'staff_roles_permissions_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(StaffRole::class, 'staff_roles_permissions');
    }
    public function staffRole()
    {
        return $this->belongsTo(StaffRole::class, 'staff_role');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profilePhoto(): HasOne
    {
        return $this->hasOne(UserProfilePhoto::class, 'user_id', 'id');
    }

//    public function staffRolesPermission(): BelongsTo
//    {
//        return $this->belongsTo(StaffRolesPermissions::class, 'staff_roles_permission_id');
//    }

}
