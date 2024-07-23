<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DncExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'paths',
        'status',
        'user_id',
        'active_count',
        'inactive_count'
    ];

    protected $casts = [
        'paths' => 'array'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
