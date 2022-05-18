<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $whatsmonster_id
 * @property string $name
 * @property string $photo_url
 * @property string $created_at
 */
class WhatsmonsterInstance extends Model
{
	use HasFactory;

	const UPDATED_AT = null;

	protected $table = 'whatsmonster_instances';

	protected static $unguarded = true;

	public function messages(): HasMany
	{
		return $this->hasMany(WhatsAppMessage::class, 'instance_id');
	}
}
