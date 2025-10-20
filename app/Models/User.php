<?php

// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Organization; // 👈 Import the Organization model


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'middle_name', 'email', 'primary_phone', 'secondary_phone', 
        'organization_id', 'state_of_posting_id', 'fcc_department_id', 'fcc_committee_id', 
        'fcc_supervisor_id', 'primary_role', 'record_status', 'first_login', 'profile_picture_file_name',
        'user_login', 'password', 'guid', 'email_verified_at', 'remember_token', 'webmail_email', 'webmail_password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function username()
    {
        return 'user_login';  // Set to 'user_login' instead of 'email'
    }

    public function getDisplayName()
    {
    return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function organization()
    {
    return $this->belongsTo(Organization::class);
    }

    public function getOrganizationName()
    {
    return optional($this->organization)->organization_name ?? 'N/A';
    }

}

