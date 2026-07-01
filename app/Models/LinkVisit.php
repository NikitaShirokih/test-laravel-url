<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\LinkVisitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class LinkVisit extends Model
{
    /** @use HasFactory<LinkVisitFactory> */
    use HasFactory;

    protected $fillable = [
        'short_link_id',
        'ip_address',
    ];

    public function shortLink(): BelongsTo
    {
        return $this->belongsTo(ShortLink::class);
    }
}
