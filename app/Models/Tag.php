<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

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
        return $this->hasMany(Article::class);
    }
}
