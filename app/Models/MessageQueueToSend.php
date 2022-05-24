<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property int $instance_id
 * @property string $phone
 * @property int $delay_before_send
 * @property string $send_at
 * @property string $created_at
 */
class MessageQueueToSend extends Model
{
	use HasFactory;

	const UPDATED_AT = null;

	protected static $unguarded = true;

	protected $table = 'messages_queue_to_send';

	protected $keyType = 'string';

	public $incrementing = false;

	public function instance(): BelongsTo
	{
		return $this->belongsTo(AccountInstance::class, 'instance_id');
	}

	protected static function booted()
	{
		parent::boot();

		static::creating(function (self $model) {
			$model->{$model->getKeyName()} = (string) Str::uuid();
		});
	}
}
