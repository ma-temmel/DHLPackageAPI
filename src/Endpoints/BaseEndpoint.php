<?php

	namespace Temmel\DHLPackageAPI\Endpoints;

	use GuzzleHttp\Exception\GuzzleException;
	use Temmel\DHLPackageAPI\Client;
	use Temmel\DHLPackageAPI\Contracts\ShouldAuthenticate;
	use Temmel\DHLPackageAPI\Exceptions\DhlParcelException;

	abstract class BaseEndpoint {
		/** @var Client */
		protected $apiClient;

		public function __construct(Client $client) {
			$this->apiClient = $client;
		}

		protected function buildQueryString(array $filters): string {
			if (empty($filters)) {
				return '';
			}

			return '?' . http_build_query($filters);
		}

		protected function getRequestHeaders(array $headers): array {
			if ($this instanceof ShouldAuthenticate) {
				$headers = array_merge($headers, [
					'Authorization' => 'Bearer ' . $this->apiClient->authentication->getAccessToken()->access_token,
					'Content-Type'  => 'application/json',
				]);
			}

			return $headers;
		}

		/**
		 * Perform an HTTP call to the API endpoint.
		 *
		 * @param string $httpMethod
		 * @param string $apiMethod
		 * @param mixed|null $httpBody
		 * @param array $requestHeaders
		 *
		 * @return string|object|null
		 *
		 * @throws DhlParcelException|GuzzleException
		 */
		protected function performApiCall(string $httpMethod, string $apiMethod, $httpBody = '', array $requestHeaders = []) {
			$response = $this->apiClient->performHttpCall(
				$httpMethod,
				$apiMethod,
				$httpBody,
				$this->getRequestHeaders($requestHeaders)
			);

			if (collect($response->getHeader('Content-Type'))->first() == 'application/pdf') {
				return $response->getBody()->getContents();
			}

			if (collect($response->getHeader('Content-Type'))->first() == 'text/xml') {
				return $response->getBody()->getContents();
			}

			$body = $response->getBody()->getContents();


			$object = @json_decode($body);


			if (json_last_error() != JSON_ERROR_NONE) {
				throw new DhlParcelException("Unable to decode DHL Package response: '$body'.");
			}

			if ($response->getStatusCode() >= 400) {
				throw DhlParcelException::createFromResponse($response);
			}

			return $object;
		}
	}
