<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title_book',
        'author',
        'publisher',
        'category',
        'stock',
        'created_by',
        'updated_by'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category', 'category');
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
