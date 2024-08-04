<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spp extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'tahun', 'nominal',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
