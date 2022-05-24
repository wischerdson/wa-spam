<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RawMessage extends Model
{
	use HasFactory;

	public $timestamps = true;

	public $incrementing = false;

	protected $table = 'raw_message';

	protected static $unguarded = true;

	protected $keyType = 'string';

	public function instance(): BelongsTo
	{
		return $this->belongsTo(AccountInstance::class, 'instance_id');
	}

	protected static function booted()
	{
		static::creating(function (self $model) {
			$model->{$model->getKeyName()} = (string) Str::uuid();
		});
	}
}
