<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'category', 'image'];

    public function getImageUrlAttribute(): string
    {
        if ($this->image && str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return 'data:image/svg+xml;utf8,' . rawurlencode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300"><rect width="100%" height="100%" fill="#EEEBFC"/><g fill="none" stroke="#4C3AE3" stroke-width="6" opacity=".55"><rect x="150" y="105" width="100" height="80" rx="10"/><circle cx="178" cy="132" r="9"/><path d="M155 180l30-30 22 20 14-12 26 22"/></g></svg>'
        );
    }
}
