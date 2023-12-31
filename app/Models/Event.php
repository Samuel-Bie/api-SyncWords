<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Authorization as Organization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_title',
        'event_start_date',
        'event_end_date',
    ];

    /**
     * Get the Authorization/organization that owns the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id',
            'id'
        );
    }
}
