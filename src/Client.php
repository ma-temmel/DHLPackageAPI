<?php

	namespace Temmel\DHLPackageAPI;

	use Composer\CaBundle\CaBundle;
	use GuzzleHttp\Client as HttpClient;
	use GuzzleHttp\Exception\ClientException;
	use GuzzleHttp\Exception\GuzzleException;
	use GuzzleHttp\Exception\RequestException;
	use GuzzleHttp\Psr7\Request;
	use GuzzleHttp\RequestOptions;
	use Psr\Http\Message\ResponseInterface;
	use Temmel\DHLPackageAPI\Endpoints\Authentication;
	use Temmel\DHLPackageAPI\Endpoints\Shipments;
	use Temmel\DHLPackageAPI\Endpoints\Tracking;
	use Temmel\DHLPackageAPI\Exceptions\DhlParcelException;


	class Client {
		/** @var string */
		protected $apiEndpoint = 'https://api-eu.dhl.com/';

		/** @var string */
		protected $clientID;

		/** @var string */
		protected $clientSecret;

		/** @var string */
		protected $userName;

		/** @var string */
		protected $userPassword;

		/** @var HttpClient */
		protected $httpClient;

		/** @var Authentication */
		public $authentication;

		/** @var Shipments */
		public $shipments;

		/** @var Tracking */
		public $tracking;
		/**
		 * @var true
		 */
		private $debug = FALSE;


		public function __construct($debug = FALSE) {
			$this->httpClient = new HttpClient([
				RequestOptions::VERIFY => CaBundle::getBundledCaBundlePath(),
			]);


			if ($debug) {
				$this->debug = TRUE;
				$this->apiEndpoint = 'https://api-sandbox.dhl.com/';
			}

			$this->initializeEndpoints();
		}

		public function initializeEndpoints(): void {
			$this->authentication = new Authentication($this);
			$this->shipments = new Shipments($this);
			$this->tracking = new Tracking($this);
		}


		/**
		 * Performs an HTTP call to the API endpoint.
		 *
		 * @param string $httpMethod
		 * @param string $apiMethod
		 * @param array|null $httpBody
		 * @param array $requestHeaders
		 *
		 * @return ResponseInterface
		 *
		 * @throws GuzzleException
		 */
		public function performHttpCall(string $httpMethod, string $apiMethod, $httpBody = '', array $requestHeaders = []): ResponseInterface {
			$url = $this->apiEndpoint . $apiMethod;
			$headers = collect([
				'Accept' => 'application/json',
			])
				->merge($requestHeaders)
				->all();

			try {
				$request = new Request($httpMethod, $url, $headers, ($requestHeaders['Content-Type'] != 'application/x-www-form-urlencoded') ? $httpBody : NULL);
			} catch (ClientException $e) {
				throw dump(DhlParcelException::createFromGuzzleRequestException($e));
			}
			try {
				$response = $this->httpClient->send($request, ($requestHeaders['Content-Type'] == 'application/x-www-form-urlencoded') ? json_decode($httpBody, TRUE) : []);
			} catch (RequestException $e) {
				throw dump(DhlParcelException::createFromGuzzleRequestException($e));
			}

			return $response;
		}

		public function apiEndpoint($apiEndpoint): self {
			$this->apiEndpoint = $apiEndpoint;
			return $this;
		}

		public function setClientID(string $value): self {
			$this->clientID = trim($value);
			return $this;
		}

		public function setClientSecret(string $value): self {
			$this->clientSecret = trim($value);
			return $this;
		}

		public function setUserName(string $value): self {
			$this->userName = trim($value);
			return $this;
		}

		public function setUserPassword(string $value): self {
			$this->userPassword = trim($value);
			return $this;
		}

		public function getDebugStatus(): bool {
			return $this->debug;
		}

		public function getClientID(): string {
			return $this->clientID;
		}

		/**
		 * @throws DhlParcelException
		 */
		public function credentials(): array {
			if (empty($this->clientSecret)) {
				throw new DhlParcelException('You have not set an API secret. Please use setClientSecret() to set the API Secret.');
			}

			if (empty($this->clientID)) {
				throw new DhlParcelException('You have not set an API key. Please use setClientID() to set the API key.');
			}
			if (empty($this->userName)) {
				throw new DhlParcelException('You have not set an API key. Please use setUserName() to set the API key.');
			}
			if (empty($this->userPassword)) {
				throw new DhlParcelException('You have not set an API key. Please use setUserPassword() to set the API key.');
			}

			return [
				'grant_type'    => 'password',
				'username'      => $this->userName,
				'password'      => $this->userPassword,
				'client_id'     => $this->clientID,
				'client_secret' => $this->clientSecret,

			];
		}


	}