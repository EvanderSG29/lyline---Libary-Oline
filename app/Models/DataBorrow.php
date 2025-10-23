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
        'type',
        'class',
        'position',
        'no_hp',
        'gender',
        'user_id',
    ];

    public function borrows()
    {
        return $this->hasMany(Borrow::class, 'data_borrow_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedPhoneNumberAttribute()
    {
        return '+62 ' . $this->no_hp;
    }
}
