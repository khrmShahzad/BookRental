<?php

namespace App\Models;

use App\Models\Category;
use App\Models\BookCategory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;
    use Sluggable;
    use SoftDeletes;

    protected $fillable = [
        'book_code', 'author', 'title', 'description', 'cover', 'slug', 'charges', 'security' ,'user_id', 'total_copies', 'available_copies'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_category', 'book_id', 'category_id');
    }

    public function bookCategories()
    {
        return $this->hasMany(BookCategory::class);
    }

    public function rentLogs(): HasMany
    {
        return $this->hasMany(RentLogs::class, 'book_id', 'id');
    }
}
