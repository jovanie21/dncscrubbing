<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionType extends Model
{
    use HasFactory;

     protected $table = 'regions_types';
     public $timestamps = false;
     protected $fillable = [
        'region_id',
        'type',
    ];
     public function region(): BelongsTo
     {
         return $this->BelongsTo(Region::class, 'region_id', 'id');
     }
}
