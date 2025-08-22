<?php

	namespace Temmel\DHLPackageAPI\Endpoints;

	use GuzzleHttp\Exception\GuzzleException;
	use Temmel\DHLPackageAPI\Exceptions\DhlParcelException;
	use Temmel\DHLPackageAPI\Resources\AccessToken;

	class Authentication extends BaseEndpoint {
		/**
		 * @var AccessToken
		 */
		protected $accessToken;

		/**
		 * Retrieve a new access token.
		 *
		 * @return AccessToken
		 * @throws DhlParcelException
		 * @throws GuzzleException
		 */
		protected function retrieveAccessToken(): AccessToken {

			$response = $this->performApiCall(
				'POST',
				'parcel/de/account/auth/ropc/v1/token',
				json_encode(['form_params' => $this->getHttpBody()]),
				['Content-Type' => 'application/x-www-form-urlencoded']
			);

			return new AccessToken($response);
		}

		/**
		 * Get the access token.
		 *
		 * @return AccessToken
		 * @throws DhlParcelException|GuzzleException
		 */
		public function getAccessToken(): AccessToken {
			if (!$this->accessToken or $this->accessToken->isExpired()) {
				$this->accessToken = $this->retrieveAccessToken();
			}

			return $this->accessToken;
		}

		/**
		 * Get the http body for the API request.
		 *
		 * @return array
		 * @throws DhlParcelException
		 */
		protected function getHttpBody(): array {
			return $this->apiClient->credentials();
		}
	}