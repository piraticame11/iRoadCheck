<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'defect',
        'lat',
        'lng',
        'location',
        'street',
        'purok',
        'barangay',
        'city',
        'date',
        'time',
        'severity',
        'label',
        'image',
        'image_annotated',
        'status',
        'report_count',
        'updated_image',
        'updater_id',
        'updated_on'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date' => 'date',
    ];

    public function severity(): BelongsTo
    {
        return $this->belongsTo(Severity::class, 'label');
    }
    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class, 'report_id');
    }
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }


    public function updater()
    {
        return $this->belongsTo(User::class, 'updater_id');
    }

    public function label()
    {
        return $this->belongsTo(Severity::class, 'label'); // 'label' here is the foreign key in reports table
    }


}
