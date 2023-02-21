<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory, Uuids;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'reason'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->hasOne(Article::class);
    }

    public function comment()
    {
        return $this->hasOne(Comment::class);
    }
}
