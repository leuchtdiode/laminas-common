<?php
namespace Common\Db;

class OrderChain
{
	/**
	 * @var Order[]
	 */
	private array $orders = [];

	public static function create(): self
	{
		return new self();
	}

	public function addOrder(Order $order) : OrderChain
	{
		$this->orders[] = $order;

		return $this;
	}

	/**
	 * @return Order[]
	 */
	public function getOrders(): array
	{
		return $this->orders;
	}
}