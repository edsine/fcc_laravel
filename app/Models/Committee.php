<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Committee extends Model
{
    use HasFactory;

    protected $table = 'committees'; // Explicit if you're not following Laravel plural conventions

    protected $fillable = [
        'committee_name',
        'secretary_user_profile_id',
        'chairman_user_profile_id',
        'record_status',
        'created',
        'created_by',
        'last_mod',
        'last_mod_by',
        'guid',
    ];

    protected $casts = [
        'created'   => 'datetime',
        'last_mod'  => 'datetime',
    ];

    // 🚀 Relationships (Assuming users table or user_profiles exists)
    
    public function chairman()
    {
        return $this->belongsTo(User::class, 'chairman_user_profile_id');
    }

    public function secretary()
    {
        return $this->belongsTo(User::class, 'secretary_user_profile_id');
    }

    // If a committee has many members (via pivot or direct), define here
    public function members()
    {
        return $this->hasMany(CommitteeMember::class, 'committee_id');
    }

    public function mdas()
    {
        //return $this->hasMany(CommitteeMda::class, 'committee_id');
    }

    // 🧠 Optional: Accessor for display name
    public function getDisplayNameAttribute()
    {
        return $this->committee_name;
    }

    // 🧪 Optional: Status accessor
    public function getIsActiveAttribute()
    {
        return $this->record_status === 'ACTIVE'; // adjust based on your app logic
    }
}
