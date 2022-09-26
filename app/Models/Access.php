<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'accesses';
    protected $access_token;
    protected $refresh_token;
    protected $expires_in;
}
