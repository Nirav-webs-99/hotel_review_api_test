<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'hotels';
    public $timestamps = true;
    /**
     * Field to be mass-assigned.
     *
     * @var array
     */
    protected $fillable = ['name', 'address', 'star', 'active'];

    /**
     * Used for get the hotel review data
     */
    public function reviewget(){
        return $this->hasMany('App\Models\Review','hotel_id','id');
    }
}
