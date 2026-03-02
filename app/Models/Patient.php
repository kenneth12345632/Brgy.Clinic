<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Patient extends Model
{
    protected $fillable = [
        'patient_id', 'first_name', 'middle_name', 'last_name', 
        'suffix', 'birthday', 'gender', 'address', 'service', 'last_visit'
    ];

    // This ensures full_name and age are included when using @json($patient)
    protected $appends = ['full_name', 'age'];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: function () {
                $mid = $this->middle_name ? " {$this->middle_name}" : "";
                $suf = $this->suffix ? " {$this->suffix}" : "";
                return "{$this->first_name}{$mid} {$this->last_name}{$suf}";
            }
        );
    }

    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birthday ? Carbon::parse($this->birthday)->age : 0
        );
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}