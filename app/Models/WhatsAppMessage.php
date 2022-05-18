<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppMessage extends Model
{
	use HasFactory;

	const UPDATED_AT = null;

	protected $table = 'whatsapp_messages';

	protected static $unguarded = true;
}
