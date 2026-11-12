<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Website er contact form (home page + contact page) theke asha message.
 */
class ContactMessage extends Model
{
    public const STATUSES = ['new' => 'New', 'read' => 'Read', 'replied' => 'Replied'];

    protected $fillable = ['name', 'phone', 'email', 'message', 'source', 'status', 'admin_note'];

    public function scopeUnread($q)
    {
        return $q->where('status', 'new');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getSourceLabelAttribute(): string
    {
        return $this->source === 'contact' ? 'Contact Page' : 'Home Page';
    }
}
