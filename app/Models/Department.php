<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'department_code',
        'department_name',
        'record_status',
        'created',
        'created_by',
        'last_mod',
        'last_mod_by',
        'guid',
    ];

    public $timestamps = false; // Set this to true if using Laravel's created_at and updated_at

    /**
     * Accessor to format full display of department (optional).
     */
    public function getDisplayNameAttribute()
    {
        return "{$this->department_code} - {$this->department_name}";
    }

    // Define any relationships here if needed (e.g., with users or organizations)
}
