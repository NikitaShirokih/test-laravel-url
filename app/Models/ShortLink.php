<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ShortLinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ShortLink extends Model
{
    /** @use HasFactory<ShortLinkFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'original_url',
        'short_code',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(LinkVisit::class);
    }

    public function shortUrl(): string
    {
        return url($this->short_code);
    }
}
