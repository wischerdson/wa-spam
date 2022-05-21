<?php

namespace App\Services\Whatsmonster;

/**
 * @property-read string $id
 * @property-read string $timestamp
 * @property-read string $instanceId
 * @property-read string|null $text
 * @property-read string|null $mediaMimeType
 * @property-read string $phone
 * @property-read bool $fromMe
 * @property-read array $rawMessage
 */
class Message
{
	private string $id;

	private string $timestamp;

	private string $instanceId;

	private ?string $text = null;

	private ?string $mediaMimeType = null;

	private string $phone;

	private bool $fromMe;

	private array $rawMessage;

	public function __construct(string $instanceId, array $rawMessage)
	{
		$this->instanceId = $instanceId;
		$this->rawMessage = $rawMessage;
		$this->parse();
	}

	public function __get(string $property)
	{
		return $this->$property;
	}

	private function parse(): void
	{
		if (!isset($this->rawMessage['message'])) {
			return;
		}

		$this->setFromMe();
		$this->setPhone();
		$this->setTimestamp();
		$this->setId();

		foreach ($this->rawMessage['message'] as $key => $value) {
			if ($key == 'conversation') {
				$this->text = $value;
				return;
			}

			if (is_array($value)) {
				if (isset($value['mimetype'])) {
					$this->mediaMimeType = $value['mimetype'];
				}

				if (isset($value['caption'])) {
					$this->text = $value['caption'];
				}
			}
		}
	}

	private function setFromMe()
	{
		$this->fromMe = (bool) $this->rawMessage['key']['fromMe'];
	}

	private function setId()
	{
		$this->id = $this->rawMessage['key']['id'];
	}

	private function setPhone()
	{
		$this->phone = explode('@', $this->rawMessage['key']['remoteJid'])[0];
	}

	private function setTimestamp()
	{
		$this->timestamp = date('Y-m-d H:i:s', $this->rawMessage['messageTimestamp']);
	}
}
