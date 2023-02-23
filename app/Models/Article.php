<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory, Uuids;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'content',
        'title',
        'slug',
        'embeds',
        'thumbnail'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function warrant()
    {
        return $this->hasOne(Warrant::class);
    }

    public function reports()
    {
        return $this->belongsToMany(Report::class);
    }
}
