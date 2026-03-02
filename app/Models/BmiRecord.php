<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BmiRecord extends Model
{
    // This allows the controller to "fill" these columns
    protected $fillable = [
        'patient_id', 
        'patient_name', 
        'age', 
        'gender', 
        'date', 
        'weight', 
        'height', 
        'bmi', 
        'category'
    ];
}