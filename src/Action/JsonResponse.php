<?php
namespace Common\Action;

use Common\Error;
use Common\Hydration\ObjectToArrayHydrator;
use Exception;
use Laminas\View\Model\JsonModel;

class JsonResponse
{
	private bool $success;

	/**
	 * @var Error[]
	 */
	private array $errors = [];

	private ?array $data = null;

	private ?Meta $meta = null;

	public static function is(): static
	{
		return new static();
	}

	/**
	 * @throws Exception
	 */
	public function dispatch(): JsonModel
	{
		return new JsonModel(
			[
				'success' => $this->success,
				'data'    => $this->data,
				'meta'    => $this->meta
					? ObjectToArrayHydrator::hydrate($this->meta)
					: null,
				'errors'  => ObjectToArrayHydrator::hydrate($this->errors),
			]
		);
	}

	public function successful(): JsonResponse
	{
		$this->success = true;

		return $this;
	}

	public function unsuccessful(): JsonResponse
	{
		$this->success = false;

		return $this;
	}

	/**
	 * @param Error[] $errors
	 */
	public function errors(array $errors): JsonResponse
	{
		$this->errors = $errors;

		return $this;
	}

	public function data(array $data): JsonResponse
	{
		$this->data = $data;

		return $this;
	}

	public function meta(?Meta $meta): JsonResponse
	{
		$this->meta = $meta;
		return $this;
	}
}
