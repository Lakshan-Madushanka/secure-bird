<?php

declare(strict_types=1);

namespace App\Models;

use App\Data\VisitData;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\LaravelData\WithData;

/**
 * @method VisitData getData()
 */
class Visit extends Model
{
    use HasFactory;
    use HasUuids;
    use WithData;

    protected string $dataClass = VisitData::class;

    protected $fillable = [
        'ip_address',
        'user_agent',
    ];

    // Relationships

    /** @return BelongsTo<Message, Visit> */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id', 'id');
    }
}
