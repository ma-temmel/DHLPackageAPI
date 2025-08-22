<?php

	namespace Temmel\DHLPackageAPI\Resources;

	use DateTimeImmutable;
	use Tightenco\Collect\Support\Collection;

	class Tracking extends BaseResource {

		/** @var string */
		public $id;

		/** @var string */
		public $service;

		/** @var Collection */
		public $destination;

		/** @var Collection */
		public $status;

		/** @var DateTimeImmutable */
		public $lastChange;

		/** @var string */
		public $statusCode;

		/** @var string */
		public $statusDescription;

		/** @var bool */
		public $isDelivered;


		/**
		 * @throws \Exception
		 */
		public function __construct(array $attributes = []) {
			#dump($attributes);
			$this->destination = new Collection();
			$this->status = new Collection();
			parent::__construct($attributes);
			if (isset($this->status->timestamp)) {
				$this->lastChange = new DateTimeImmutable($this->status->timestamp);
			}
			if (isset($this->status->statusCode)) {
				$this->statusCode = $this->status->statusCode;
			}
			if (isset($this->status->description)) {
				$this->statusDescription = $this->status->description;
			}
			if (isset($this->status->statusCode)) {
				$this->isDelivered = ($this->status->statusCode == 'delivered') ?? FALSE;
			}
		}
	}