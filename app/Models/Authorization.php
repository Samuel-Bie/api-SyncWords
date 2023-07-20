<?php

namespace App\Models;

use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Authorization extends Model
{
    use HasFactory;
    use HasApiTokens;

    /**
     * Get all of the events for the Authorization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(
            Event::class,
            'organization_id'
        );
    }
}
