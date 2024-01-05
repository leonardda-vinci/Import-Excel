<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['ClientName', 'DateStart', 'DateEnd', 'Status'];
}
