<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
     protected $primaryKey = 'patient_id'; // change this to your actual column name
    // These MUST match the 'name' attributes in your HTML form exactly
    protected $fillable = [
    'first_name',
    'middle_name',
    'last_name',
    'birthday',
    'gender',
    'service',
    'address'
];
}