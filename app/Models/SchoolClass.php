<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nama_kelas', 'jurusan',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
