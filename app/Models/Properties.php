<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Properties extends Model
{
    use HasFactory;

    protected $guarded = [];

    // protected $appends = ['formatted_start_time', 'formatted_end_time'];

    protected $casts = [
        // 'start_time' => 'datetime',
        // 'end_time' => 'datetime',
        // 'status' => AppointmentStatus::class,
    ];

    // public function client(): BelongsTo
    // {
    //     return $this->belongsTo(Client::class);
    // }

    public function formattedStartTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->format('Y-m-d h:i A'),
        );
    }

    public function formattedEndTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->updated_at->format('Y-m-d h:i A'),
        );
    }

    public function type()
    {
        return $this->belongsTo(PropertyTypes::class, 'property_type_id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenities::class, 'property_amenity_pivot', 'property_id', 'amenity_id');
    }

    public function features()
    {
        return $this->belongsToMany(Features::class, 'property_feature_pivot', 'property_id', 'feature_id');
    }

    public function lineitems()
    {
        return $this->hasMany(PropertyLineItem::class, 'property_id');
    }

}
