<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $instance_id
 * @property string $event_type
 * @property string $data
 * @property string $created_at
 */
class WhatsmonsterLog extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

	protected $table = 'whatsmonster_logs';

	protected static $unguarded = true;

    public function instance(): BelongsTo
    {
        return $this->belongsTo(WhatsmonsterInstance::class, 'instance_id');
    }
}
