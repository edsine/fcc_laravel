<?php

// app/Models/Setting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Define the table name (optional, Laravel assumes plural model names)
    protected $table = 'settings';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'provider_name', // e.g., Gmail, Yahoo, etc.
        'hostname',      // e.g., '{imap.gmail.com:993/imap/ssl}INBOX'
        'port',          // IMAP port (usually 993 for secure IMAP)
        'ssl',           // SSL enabled or not (boolean)
    ];

    // If you need to cast any attributes (e.g., cast 'ssl' to boolean)
    protected $casts = [
        'ssl' => 'boolean', // Ensure the 'ssl' field is treated as a boolean
    ];
}

