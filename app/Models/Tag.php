<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, Uuids;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
    ];

    public function parent()
    {
        return $this->hasOne(Tag::class);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
