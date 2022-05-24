<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $instance_id
 * @property string $external_id
 * @property string $text
 * @property bool $from_me
 * @property string $phone
 * @property string $media_mime_type
 * @property string $created_at
 */
class Message extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $table = 'messages';

	protected static $unguarded = true;
}
