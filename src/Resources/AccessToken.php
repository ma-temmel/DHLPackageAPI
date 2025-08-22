<?php

	namespace Temmel\DHLPackageAPI\Resources;

	use DateTimeImmutable;

	class AccessToken {

		/** @var array */
		public $access_token;

		/** @var int */
		public $expiresAt;

		public function __construct(object $response) {
			$this->access_token = $response->access_token;
			$this->expiresAt = (new DateTimeImmutable())->modify('+ ' . $response->expires_in . ' seconds');
			# new \Temmel\DHLPackageAPI\Debug\Debug($response);
		}


		public function isExpired(): int {
			if (new DateTimeImmutable() > $this->expiresAt) {
				// Token ist abgelaufen
				return TRUE;
			}
			return FALSE;
		}
	}
