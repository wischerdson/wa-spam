<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $external_id
 * @property string $name
 * @property string $photo_url
 * @property string $created_at
 */
class AccountInstance extends Model
{
	use HasFactory;

	const UPDATED_AT = null;

	protected $table = 'account_instances';

	protected static $unguarded = true;

	public function messages(): HasMany
	{
		return $this->hasMany(Message::class, 'instance_id');
	}
}
