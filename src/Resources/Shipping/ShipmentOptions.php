<?php

	namespace Temmel\DHLPackageAPI\Resources\Shipping;

	use Temmel\DHLPackageAPI\Resources\BaseResource;

	class ShipmentOptions extends BaseResource {
		/** @var string */
		protected $delivery_type;

		/** @var bool */
		public $only_recipient;

		/** @var bool */
		public $extra_assurance;

		/** @var bool */
		public $evening_delivery;

		/** @var bool */
		public $signature;

		/** @var array|mixed */
		public $details;

		/**
		 * @var array|mixed
		 */
		public $services;

		/**
		 * @var array|mixed
		 */
		public $customs;

		public function __construct(array $attributes = []) {
			$this->setDefaultOptions();

			parent::__construct($attributes);
		}

		public function setDefaultOptions(): self {
			$this->delivery_type = 'DOOR';
			$this->signature = FALSE;
			$this->only_recipient = FALSE;
			$this->extra_assurance = FALSE;
			$this->evening_delivery = FALSE;

			$this->details = [
				'dim'    => [
					'uom'    => 'mm',
					'height' => '100',
					'width'  => '150',
					'length' => '200',
				],
				'weight' => [
					'uom'   => 'g',
					'value' => 500,
				],
			];
			$this->services = ['premium' => 0, 'goGreenPlus' => 1];
			$this->customs = [];

			return $this;
		}


		public function detailsToArray(): array {
			return collect(
				$this->details
			)->all();
		}

		public function servicesToArray(): ?array {
			if (!empty($this->services)) {
				return collect(
					$this->services
				)->all();
			}
			return NULL;
		}

		public function customsToArray(): ?array {
			if (!empty($this->customs)) {
				return collect(
					$this->customs
				)->all();
			}
			return NULL;
		}
	}
