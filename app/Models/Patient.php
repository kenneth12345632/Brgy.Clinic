<?php

namespace App\Models; // <--- Double check this line!

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'patient_id', 'name', 'age', 'gender', 'address', 'service', 'last_visit'
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}