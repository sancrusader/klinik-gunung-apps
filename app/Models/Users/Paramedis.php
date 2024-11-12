<?php

namespace App\Models\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paramedis extends Model
{
    use HasFactory;

    protected $table = 'paramedis';

    protected $fillable = [
        'user_id',
        'nik',
        'email',
        'name',
        'address',
        'date_of_birth',
        'phone',
        'role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
