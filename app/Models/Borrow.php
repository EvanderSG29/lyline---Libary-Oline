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
        'user_id',
        'book_id',
        'borrow_date',
        'return_date',
        'status',
        'notification_sent',
    ];

    protected $casts = [
        'borrow_date' => 'datetime',
        'return_date' => 'datetime',
        'borrow_date' => 'date',
        'return_date' => 'date',
        'notification_sent' => 'boolean',
    ];

    public function user()
    /**
     * Get the user that owns the borrow.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
        return $this->belongsTo(User::class);
    }

    public function book()
    /**
     * Get the book that was borrowed.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function dataBorrow()
    /**
     * Determine if the borrow is approaching its return date.
     * This logic aligns with the controller's 'approaching' filter:
     * return_date <= now()->addDays(2) AND return_date > now()
     */
    public function isApproaching(): bool
    {
        return $this->belongsTo(DataBorrow::class, 'data_borrow_id');
        if ($this->status !== 'borrowed' || !$this->return_date) {
            return false;
        }

        $now = Carbon::now();
        $twoDaysFromNow = $now->copy()->addDays(2);

        // Check if return_date is after current moment AND within the next two days (inclusive)
        return $this->return_date->isAfter($now) && $this->return_date->lessThanOrEqualTo($twoDaysFromNow);
    }

    /**
     * Get the status color based on borrow status and dates.
     * Blue: Book still borrowed (not returned and not past deadline)
     * Green: Book returned
     * Orange: Approaching the return deadline (e.g., two days before)
     * Red: Past the return deadline
     * Determine if the borrow is overdue.
     * This logic aligns with the controller's 'overdue' filter:
     * return_date < now()
     */
    public function getStatusColorAttribute()
    public function isOverdue(): bool
    {
        if ($this->status !== 'borrowed' || !$this->return_date) {
            return false;
        }

        // Check if return_date is before the current moment
        return $this->return_date->isBefore(Carbon::now());
    }

    /**
     * Get the status color attribute.
     */
    public function getStatusColorAttribute(): string
    {
        if ($this->status === 'returned') {
            return 'green';
        }

        $now = now();
        $returnDate = $this->return_date;
        if ($this->isOverdue()) {
            return 'red';
        }

        if (!$returnDate) {
            return 'blue'; // No return date set, assume borrowed
        if ($this->isApproaching()) {
            return 'orange';
        }

        $daysUntilDue = $now->diffInDays($returnDate, false); // Negative if overdue

        if ($daysUntilDue < 0) {
            return 'red'; // Overdue
        } elseif ($daysUntilDue <= 2) {
            return 'orange'; // Approaching deadline
        } else {
            return 'blue'; // Borrowed, not approaching
        }
        return 'blue'; // Default for borrowed and not approaching/overdue
    }

    /**
     * Get status details for tooltips and accessibility.
     * Get the status icon attribute.
     */
    public function getStatusDetailsAttribute()
    public function getStatusIconAttribute(): string
    {
        $color = $this->status_color;

        switch ($color) {
            case 'green':
                return 'Book returned';
            case 'blue':
                $days = $this->return_date ? now()->diffInDays($this->return_date, false) : null;
                return $days !== null ? "Due in {$days} days" : 'Borrowed';
            case 'orange':
                $days = now()->diffInDays($this->return_date, false);
                return "Approaching deadline: Due in {$days} days";
            case 'red':
                $days = abs(now()->diffInDays($this->return_date, false));
                return "Overdue by {$days} days";
            default:
                return 'Unknown status';
        }
        return match ($this->status_color) {
            'green' => 'check-circle-fill',
            'red' => 'x-circle-fill',
            'orange' => 'exclamation-triangle-fill',
            default => 'arrow-repeat', // blue
        };
    }

    /**
     * Get icon for status (for accessibility).
     * Get the status details attribute.
     */
    public function getStatusIconAttribute()
    public function getStatusDetailsAttribute(): string
    {
        $color = $this->status_color;

        switch ($color) {
            case 'green':
                return 'check-circle'; // Bootstrap icon
            case 'blue':
                return 'book';
            case 'orange':
                return 'exclamation-triangle';
            case 'red':
                return 'x-circle';
            default:
                return 'question-circle';
        }
        return match ($this->status_color) {
            'green' => 'Returned on time.',
            'red' => 'This book is overdue.',
            'orange' => 'Return date is approaching.',
            default => 'Currently borrowed.', // blue
        };
    }
}
