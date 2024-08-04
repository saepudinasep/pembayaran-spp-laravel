<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id', 'nisn', 'nis', 'nama', 'school_class_id', 'alamat', 'no_telp', 'spp_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function spp()
    {
        return $this->belongsTo(Spp::class);
    }
}
