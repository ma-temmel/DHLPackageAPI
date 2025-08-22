<?php

	namespace Temmel\DHLPackageAPI\Resources;

	use Temmel\DHLPackageAPI\Debug\Debug;
	use Temmel\DHLPackageAPI\Resources\Address\Recipient;
	use Temmel\DHLPackageAPI\Resources\Shipping\ShipmentOptions;

	class Package extends BaseResource {
		/** @var ShipmentOptions */
		public $options;

		/** @var Recipient */
		public $recipient;

		/** @var Recipient */
		public $sender;

		public $profile;

		public $refNo;

		public $billingNumber;

		public $product;

		public function __construct(array $attributes = []) {
			$this->profile = 'STANDARD_GRUPPENPROFIL';
			$this->product = 'V01PAK';
			$this->billingNumber = '';

			$this->options = new ShipmentOptions;
			$this->recipient = new Recipient;
			$this->sender = new Recipient;



			parent::__construct($attributes);
		}

		/**
		 * Set the recipient for this parcel.
		 *
		 * @param Recipient|array $value
		 *
		 * @return void
		 */
		public function setRecipientAttribute($value): void {
			if ($value instanceof Recipient) {
				$this->recipient = $value;

				return;
			}

			$this->recipient->fill($value);
		}

		/**
		 * Set the sender for this parcel.
		 *
		 * @param Recipient|array $value
		 *
		 * @return void
		 */
		public function setSenderAttribute($value): void {
			if ($value instanceof Recipient) {
				$this->sender = $value;

				return;
			}

			$this->sender->fill($value);
		}


		public function toArray(): array {

			$shipments = [
				'product'       => $this->product,
				'billingNumber' => $this->billingNumber,
				'refNo'         => $this->refNo,
				'shipper'       => $this->sender->toArray(),
				'consignee'     => $this->recipient->toArray(),
				'details'       => $this->options->detailsToArray(),
				'services'      => $this->options->servicesToArray(),

			];

			if (!empty($this->options->customs)) {
				Debug::debug($this->options->customs);
				$shipments['customs'] = $this->options->customsToArray();
			}


			return collect([
				'profile'   => $this->profile,
				'shipments' => [$shipments],
			])->all();
		}
	}
