<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryUpdate extends Model
{
    protected $fillable = [
        'reporter_id',
        'date',
        'time',
        'image',
        'lat',
        'lng',
        'image_name',
    ];
    public function suggestions()
    {
        return $this->hasMany(Suggestion::class, 'report_id');
    }
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
