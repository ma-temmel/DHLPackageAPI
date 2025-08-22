<?php

	namespace Temmel\DHLPackageAPI\Resources\Address;

	class Recipient extends Address {
		/** @var string */
		public $name1;

		/** @var string */
		public $name2;

		/** @var string */
		public $name3;

		/** @var string */
		public $phone;

		/** @var string */
		public $contactName;

		/** @var string */
		public $email;

		/** @var string */
		public $shipperRef;


		public function __construct() {
			parent::__construct();
		}

		public function toArray(): array {
			return collect([
				'name1'         => $this->name1,
				'addressStreet' => $this->addressStreet,
				'postalCode'    => $this->postalCode,
				'city'          => $this->city,
				'country'       => $this->country,
			])
				->when(!empty($this->shipperRef), function ($collection) {
					return $collection->put('shipperRef', $this->shipperRef);
				})
				->when(!empty($this->name2), function ($collection) {
					return $collection->put('name2', $this->name2);
				})
				->when(!empty($this->name3), function ($collection) {
					return $collection->put('name3', $this->name3);
				})
				->when(!empty($this->contactName), function ($collection) {
					return $collection->put('contactName', $this->contactName);
				})
				->when(!empty($this->addressHouse), function ($collection) {
					return $collection->put('addressHouse', $this->addressHouse);
				})
				->when(!empty($this->state), function ($collection) {
					return $collection->put('state', $this->state);
				})
				->when(!empty($this->email), function ($collection) {
					return $collection->put('email', $this->email);
				})
				->when(!empty($this->phone), function ($collection) {
					return $collection->put('phoneNumber', $this->phone);
				})
				->when(!empty($this->additionalAddressInformation1), function ($collection) {
					return $collection->put('additionalAddressInformation1', $this->additionalAddressInformation1);
				})
				->when(!empty($this->additionalAddressInformation2), function ($collection) {
					return $collection->put('additionalAddressInformation2', $this->additionalAddressInformation2);
				})
				->all();
		}
	}
