<?php

	namespace Temmel\DHLPackageAPI\Resources;

	use Tightenco\Collect\Support\Collection;

	class Shipment extends BaseResource {
		/** @var string */
		public $shipmentNo;

		/** @var string */
		public $shipmentRefNo;

		/** @var string */
		public $routingCode;

		/** @var Collection */
		public $label;


		public function __construct(array $attributes = []) {
			$this->label = new Collection;
			parent::__construct($attributes);
		}
	}