<?php
namespace Common\Router;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Method;
use Laminas\Router\Http\Segment;

class HttpRouteCreator
{
	private ?string $route = null;

	private ?string $action = null;

	private bool $mayTerminate = true;

	private ?array $constraints = null;

	private ?array $childRoutes = null;

	private array $methods = [];

	public static function create(): self
	{
		return new self();
	}

	public function setRoute(string $route): HttpRouteCreator
	{
		$this->route = $route;

		return $this;
	}

	public function setAction(string $action): HttpRouteCreator
	{
		$this->action = $action;

		return $this;
	}

	public function setMayTerminate(bool $mayTerminate): HttpRouteCreator
	{
		$this->mayTerminate = $mayTerminate;

		return $this;
	}

	public function setConstraints(array $constraints): HttpRouteCreator
	{
		$this->constraints = $constraints;

		return $this;
	}

	public function setChildRoutes(array $childRoutes): HttpRouteCreator
	{
		$this->childRoutes = $childRoutes;

		return $this;
	}

	public function setMethods(array $methods): HttpRouteCreator
	{
		$this->methods = $methods;

		return $this;
	}

	public function getConfig(): array
	{
		$type = Literal::class;

		if ($this->constraints)
		{
			$type = Segment::class;
		}

		if ($this->methods)
		{
			$type = Method::class;
		}

		return [
			'type'          => $type,
			'may_terminate' => $this->mayTerminate,
			'options'       => [
				'route'       => $this->route,
				'verb'        => implode(',', $this->methods),
				'defaults'    => [
					'controller' => $this->action,
					'action'     => 'execute',
				],
				'constraints' => $this->constraints,
			],
			'child_routes'  => $this->childRoutes,
		];
	}
}
