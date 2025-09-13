<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'size',
        'color',
        'brand',
        'condition',
        'image_path',
        'user_id',
        'crew_id'
    ];

    // Relationship: Item belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method to get image URL
    public function getImageUrl()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return asset('images/placeholder-item.jpg'); // We'll create this
    }
}