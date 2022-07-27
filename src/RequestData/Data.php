<?php
namespace Common\RequestData;

use Common\RequestData\Error\PropertyIsInvalid;
use Common\RequestData\Error\PropertyIsMandatory;
use Common\RequestData\PropertyDefinition\PropertyDefinition;
use Common\Translator;
use Common\Util\StringUtil;
use Exception;
use JetBrains\PhpStorm\Pure;
use Laminas\Filter\FilterInterface;
use Laminas\Stdlib\RequestInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class Data
{
	/**
	 * @return PropertyDefinition[]
	 */
	abstract protected function getDefinitions(): array;

	protected ContainerInterface $container;

	private array $data;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function setRequest(RequestInterface $request): Data
	{
		if ($request->getMethod() === 'GET')
		{
			$this->data = $request
				->getQuery()
				->toArray();
		}
		else
		{
			if (($content = $request->getContent()))
			{
				$this->data = json_decode($content, JSON_OBJECT_AS_ARRAY);

				if (!$this->data) // try again POST data
				{
					parse_str($content, $this->data);
				}
			}

			if ($this->data === null)
			{
				$this->data = [];
			}
		}

		return $this;
	}

	public function setData(array $data): self
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function getValues(): Values
	{
		$values = new Values();

		$this->handleDefinitions(
			$values,
			$this->getDefinitions()
		);

		return $values;
	}

	/**
	 * @param PropertyDefinition[] $definitions
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	private function handleDefinitions(Values $values, array $definitions): void
	{
		foreach ($definitions as $definition)
		{
			$handledValue = $this->handleValue($definition);
			$rawValue     = $handledValue['value'];

			if (($filterChain = $definition->getFilterChain()))
			{
				/**
				 * @var FilterInterface $filter
				 */
				foreach ($filterChain->getFilters() as $filter)
				{
					$rawValue = $filter->filter($rawValue);
				}
			}

			$value = new Value();
			$value->setName($definition->getName());
			$value->setValue($rawValue);
			$value->setPresent($handledValue['present']);

			$values->addValue($value);

			if ($rawValue === null && !$definition->isRequired())
			{
				if (($defaultValue = $definition->getDefaultValue()) !== null)
				{
					$rawValue = $defaultValue;
				}
				else
				{
					continue;
				}
			}

			if ($definition->valueIsEmpty($rawValue) && $definition->isRequired())
			{
				$value->addError(
					PropertyIsMandatory::create(
						$this->getErrorLabel($definition)
					)
				);

				continue;
			}

			$transformerClass = $definition->getTransformer();

			if ($transformerClass)
			{
				$transformer = $this->container->get($transformerClass);

				if (!$transformer)
				{
					throw new Exception('Transformer ' . $transformerClass . ' is not available');
				}

				try
				{
					$rawValue = $transformer->transform($rawValue);

					$value->setValue($rawValue);
				}
				catch (Exception $ex)
				{
					$value->setValue(null);

					$value->addError(
						PropertyIsInvalid::create(
							$this->getErrorLabel($definition),
							$ex->getMessage()
						)
					);

					continue;
				}
			}

			$validatorChain = $definition->getValidatorChain();

			if ($validatorChain && !$validatorChain->isValid($rawValue))
			{
				$value->setValue(null);

				foreach ($validatorChain->getMessages() as $message)
				{
					$value->addError(
						PropertyIsInvalid::create(
							$this->getErrorLabel($definition),
							$message
						)
					);
				}
			}
		}
	}

	private function handleValue(PropertyDefinition $definition): array
	{
		$name = $definition->getName();

		if (StringUtil::contains($name, '.')) // nested property
		{
			$arrayIndexes = explode('.', $name);

			$rawValue = $this->data[$arrayIndexes[0]] ?? null;
			$present  = array_key_exists($arrayIndexes[0], $this->data);

			if ($rawValue === null)
			{
				return [
					'present' => $present,
					'value'   => null,
				];
			}

			foreach (array_slice($arrayIndexes, 1) as $arrIndex)
			{
				$present  = array_key_exists($arrIndex, $rawValue);
				$rawValue = $rawValue[$arrIndex] ?? null;

				if ($rawValue === null)
				{
					return [
						'present' => $present,
						'value'   => null,
					];
				}
			}

			return [
				'present' => $present,
				'value'   => $rawValue,
			];
		}

		return [
			'present' => array_key_exists($name, $this->data),
			'value'   => $this->data[$name] ?? null,
		];
	}

	private function getErrorLabel(PropertyDefinition $definition): string
	{
		return ($label = $definition->getLabel())
			? Translator::translate($label)
			: $definition->getName();
	}
}
