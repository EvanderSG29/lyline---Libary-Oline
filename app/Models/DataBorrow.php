<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBorrow extends Model
{
    use HasFactory;

    protected $table = 'databorrows';

    protected $fillable = [
        'name_borrower',
        'class',
        'no_hp',
        'gender',
    ];

    public function borrows()
    {
        return $this->hasMany(Borrow::class, 'data_borrow_id');
    }
}
