<?php

	namespace Temmel\DHLPackageAPI\Endpoints;

	use Temmel\DHLPackageAPI\Contracts\ShouldAuthenticate;
	use Temmel\DHLPackageAPI\Resources\Package;
	use Temmel\DHLPackageAPI\Resources\Shipment as ShipmentResource;

	class Shipments extends BaseEndpoint implements ShouldAuthenticate {
		public function create(Package $package): ShipmentResource {

			$response = $this->performApiCall(
				'POST',
				'parcel/de/shipping/v2/orders?validate=false&mustEncode=false&docFormat=PDF&printFormat=910-300-410&combine=true',
				$this->getHttpBody($package),
				['Content-Type' => 'application/json']
			);

			return new ShipmentResource([
				'shipmentNo'    => $response->items[0]->shipmentNo,
				'shipmentRefNo' => $response->items[0]->shipmentRefNo,
				'routingCode'   => $response->items[0]->routingCode,
				'label'         => $response->items[0]->label,
			]);
		}


		public function get(string $package): ShipmentResource {
			$response = $this->performApiCall(
				'GET',
				'parcel/de/shipping/v2/orders?docFormat=PDF&printFormat=910-300-410&shipment=' . $package,
				'',
				['Content-Type' => 'application/json']
			);

			return new ShipmentResource([
				'shipmentNo'    => $response->items[0]->shipmentNo,
				'shipmentRefNo' => $response->items[0]->shipmentRefNo,
				'routingCode'   => $response->items[0]->routingCode,
				'label'         => $response->items[0]->label,
			]);
		}

		protected function getHttpBody(Package $package): string {
			return json_encode($package->toArray());
		}


		public function manifest($package) {
			return $this->performApiCall(
				'POST',
				'parcel/de/shipping/v2/manifests',
				json_encode($package),
				['Content-Type' => 'application/json']
			);
		}
	}
