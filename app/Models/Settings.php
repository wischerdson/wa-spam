<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $key
 * @property string $value
 */
class Settings extends Model
{
	use HasFactory;

	public $timestamps = false;

	public $incrementing = false;

	protected static $unguarded = true;

	protected $table = 'settings';

	protected $primaryKey = 'key';

	protected $keyType = 'string';
}
