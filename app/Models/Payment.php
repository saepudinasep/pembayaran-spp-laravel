<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id', 'student_id', 'tgl_bayar', 'bulan_dibayar', 'tahun_dibayar', 'spp_id', 'jumlah_bayar', 'midtrans_transaction_id', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function spp()
    {
        return $this->belongsTo(Spp::class);
    }
}
