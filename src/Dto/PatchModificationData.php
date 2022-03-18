<?php
namespace Common\Dto;

abstract class PatchModificationData
{
	/**
	 * @var string[]
	 */
	protected array $modifications = [];

	public function shouldModify(string $id): bool
	{
		return in_array($id, $this->modifications);
	}
}