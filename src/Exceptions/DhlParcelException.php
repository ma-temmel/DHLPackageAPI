<?php

	namespace Temmel\DHLPackageAPI\Exceptions;

	use Exception;
	use GuzzleHttp\Exception\RequestException;
	use GuzzleHttp\Psr7\Response;
	use Psr\Http\Message\ResponseInterface;
	use Throwable;

	class DhlParcelException extends Exception {
		/** @var Response */
		protected $response;

		/**
		 * Create a new DhlParcelException instance.
		 *
		 * @param string $message
		 * @param int $code
		 * @param Response|null $response
		 * @param Throwable|null $previous
		 */
		public function __construct(string $message = '', int $code = 0, ResponseInterface $response = NULL, Throwable $previous = NULL) {
			parent::__construct($message, $code, $previous);

			$this->response = $response;
		}

		/**
		 *  Create a new DhlParcelException instance from the given Guzzle request exception.
		 *
		 * @param RequestException $exception
		 *
		 * @return DhlParcelException
		 */
		public static function createFromGuzzleRequestException(RequestException $exception): DhlParcelException {
			return new static(
				$exception->getMessage(),
				$exception->getCode(),
				$exception->getResponse(),
				$exception
			);
		}

		/**
		 * Create a new DhlParcelException instance from the given response.
		 *
		 * @param ResponseInterface $response
		 * @param Throwable|null $previous
		 *
		 * @return static
		 * @throws DhlParcelException
		 */
		public static function createFromResponse(ResponseInterface $response, Throwable $previous = NULL): DhlParcelException {
			$object = static::parseResponseBody($response);

			var_dump($object);

			return new static(
				'Error executing API call: ' . $object->message,
				$response->getStatusCode(),
				$response,
				$previous
			);
		}

		public function hasResponse(): bool {
			return $this->response !== NULL;
		}

		public function getResponse(): ?ResponseInterface {
			return $this->response;
		}

		/**
		 * Parse the body of a response.
		 *
		 * @param ResponseInterface $response
		 *
		 * @return object
		 * @throws DhlParcelException
		 */
		protected static function parseResponseBody(ResponseInterface $response): object {
			$body = (string)$response->getBody();

			$object = @json_decode($body);

			if (json_last_error() !== JSON_ERROR_NONE) {
				throw new static("Unable to decode DHL Package response: '$body'.");
			}

			return $object;
		}
	}
