<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organization'; // Optional: only needed if table is not plural

    protected $fillable = [
        'establishment_code',
        'establishment_mnemonic',
        'organization_name',
        'level_of_government',
        'establishment_type_id',
        'state_owned_establishment_state_id',
        'state_of_location_id',
        'year_of_establishment',
        'contact_address',
        'website_address',
        'email_address',
        'primary_phone',
        'parent_organization_id',
        'fcc_committee_id',
        'fcc_desk_officer_id',
        'record_status',
        'created',
        'created_by',
        'last_mod',
        'last_mod_by',
        'guid',
        'is_client',
    ];

    protected $casts = [
        'created' => 'datetime',
        'last_mod' => 'datetime',
        'is_client' => 'boolean',
    ];

    /**
     * Example Accessor: Full Establishment Name
     */
    public function getFullEstablishmentNameAttribute()
    {
        return "{$this->establishment_code} - {$this->organization_name}";
    }

    /**
     * Example Relationship (if applicable)
     */
    public function parentOrganization()
    {
        return $this->belongsTo(Organization::class, 'parent_organization_id');
    }

    public function fccCommittee()
    {
        return $this->belongsTo(Committee::class, 'fcc_committee_id');
    }

    public function fccDeskOfficer()
    {
        return $this->belongsTo(User::class, 'fcc_desk_officer_id');
    }
}
