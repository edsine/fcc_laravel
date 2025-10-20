<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommitteeMember extends Model
{
    use HasFactory;

    protected $table = 'committee_members';

    protected $fillable = [
        'committee_id',
        'staff_user_profile_id',
    ];

    public $timestamps = false; // If the table doesn't have created_at / updated_at columns

    // 🔁 Relationships

    public function committee()
    {
        return $this->belongsTo(Committee::class, 'committee_id');
    }

    public function userProfile()
    {
        return $this->belongsTo(User::class, 'staff_user_profile_id');
    }

    // 🎯 Optional Accessor: Full name (assuming first and last names exist on related model)
    public function getMemberFullNameAttribute()
    {
        return $this->userProfile ? $this->userProfile->first_name . ' ' . $this->userProfile->last_name : null;
    }
}
