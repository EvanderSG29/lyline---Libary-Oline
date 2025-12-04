<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Borrow extends Model
{
    /** @use HasFactory<\Database\Factories\BorrowFactory> */
    use HasFactory;

    protected $fillable = [
        'borrow_id',
        'user_id',
        'book_id',
        'borrow_date',
        'return_date',
        'status',
        'notification_sent',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'notification_sent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
