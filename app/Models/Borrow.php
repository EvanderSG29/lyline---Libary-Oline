<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    /** @use HasFactory<\Database\Factories\BorrowFactory> */
    use HasFactory;

    protected $fillable = [
        'data_borrow_id',
        'book_id',
        'borrow_date',
        'return_date',
        'status',
    ];

    public function dataBorrow()
    {
        return $this->belongsTo(DataBorrow::class, 'data_borrow_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
