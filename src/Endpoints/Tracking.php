<?php

	namespace Temmel\DHLPackageAPI\Endpoints;

	use Temmel\DHLPackageAPI\Resources\Tracking as TrackingResource;

	class Tracking extends BaseEndpoint {
		public function get($trackingNumber, $plz = NULL): TrackingResource {

			#dump($trackingNumber);

			$shippingNumbers['language'] = 'de';
			$shippingNumbers['service'] = 'parcel-de';

			$shippingNumbers['trackingNumber'] = (string)$trackingNumber;
			if (!empty($plz)) {
				$shippingNumbers['recipientPostalCode'] = (string)$plz;
			}


			if ($this->apiClient->getDebugStatus()) {
				$this->apiClient->apiEndpoint('https://api-test.dhl.com/');
				$this->apiClient->setClientID('demo-key');
			}


			#dump($shippingNumbers);

			$response = $this->performApiCall(
				'GET',
				'track/shipments' . $this->buildQueryString($shippingNumbers),
				/*json_encode(['Auth' => $this->apiClient->getClientID(),$this->apiClient->getClientSecret()]),*/
				'',
				['Content-Type' => 'application/json', 'DHL-API-KEY' => $this->apiClient->getClientID()]
			);

			return new TrackingResource(
				collect(collect($response)->first()[0])->all()
			);
		}
	}