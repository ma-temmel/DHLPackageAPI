<?php

	namespace Temmel\DHLPackageAPI\Resources\Address;

	use Temmel\DHLPackageAPI\Resources\BaseResource;

	class Address extends BaseResource {
		/** @var string */
		public $addressStreet;

		/** @var string|int */
		public $addressHouse;

		/** @var string */
		public $postalCode;

		/** @var string */
		public $city;

		/** @var string */
		public $state;

		/** @var string */
		public $country;

		/** @var string */
		public $additionalAddressInformation1;

		/** @var string */
		public $additionalAddressInformation2;

		public function toArray(): array {
			return collect(parent::toArray())->all();
		}
	}
