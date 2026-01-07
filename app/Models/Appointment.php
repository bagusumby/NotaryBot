<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'notes',
        'booking_date',
        'booking_time',
        'status',
        'employee_id',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    /**
     * Get the employee assigned to this appointment
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
