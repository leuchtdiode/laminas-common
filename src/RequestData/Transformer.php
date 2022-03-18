<?php
namespace Common\RequestData;

interface Transformer
{
	public function transform(mixed $value): mixed;
}