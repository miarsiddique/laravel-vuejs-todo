<?php

namespace App\Core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ClientGuzzul
{
	/**
	 * http client
	 * @var Client
	 */
	private $client;

	/**
	 * base url for realmailer
	 * @var String
	 */
	private $resourceUri;

	/**
	 * Response
	 * @var Mixed
	 */
	private $result;

	/**
	 * create new instance
	 * @param Client $client
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
		$this->resourceUri = env('APP_URL');
	}

	/**
	 * post request to realmailer
	 * @param  String $endpoint
	 * @param  array  $payload
	 *
	 * @return Response
	 */
	public function post($endpoint,array $payload =[])
	{
		$url = implode('/', [$this->resourceUri, $endpoint]);

		$headers = [
			'Accept' => 'application/json',
            'Content-type' => 'application/json',
		];

		$result = $this->client->request('POST', $url, ['headers' => $headers, 'body'=>json_encode($payload)]);

		return $this->getResponseBody($result);
	}

	/**
	 * apply headers for client
	 * @param  array  $params
	 * @return array
	 */
	private function applyHeaders(array $params=[])
	{
		$headers = [
			'x-api-key' => env('ACCESS_TOKEN'),
			'content-type' => 'application/json',
		];

		$params['headers'] = $headers;

		return $params;
	}

	/**
	 * return response body
	 *
	 * @return array
	 */
	public function getResponseBody($result)
	{
		return json_decode($result->getBody(), true);
	}


}