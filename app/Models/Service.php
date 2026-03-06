<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    public function patients()
{
    // This links the Service 'name' to the Patient 'service' column
    return $this->hasMany(\App\Models\Patient::class, 'service', 'name');
}
}
