<?php
namespace Common\RequestData\PropertyDefinition;

use Laminas\Filter\FilterChain;
use Laminas\Filter\FilterInterface;
use Laminas\Validator\ValidatorChain;
use Laminas\Validator\ValidatorInterface;

abstract class PropertyDefinition
{
	public const string TRANSFORMER_EXECUTION__BEFORE_VALIDATION = 'before-validation';
	public const string TRANSFORMER_EXECUTION__AFTER_VALIDATION  = 'after-validation';

	protected string $name;

	/**
	 * @var string|null
	 */
	protected ?string $label = null;

	protected mixed $defaultValue = null;

	protected bool $required;

	protected ?ValidatorChain $validatorChain = null;

	protected ?FilterChain $filterChain = null;

	protected ?string $transformer          = null;
	protected ?string $transformerExecution = self::TRANSFORMER_EXECUTION__BEFORE_VALIDATION;

	/**
	 */
	public function __construct()
	{
		$this->validatorChain = new ValidatorChain();
	}

	public function valueIsEmpty($value): bool
	{
		return empty($value);
	}

	public function addValidator(ValidatorInterface $validator): self
	{
		if (!$this->validatorChain)
		{
			$this->validatorChain = new ValidatorChain();
		}

		$this->validatorChain->attach($validator);

		return $this;
	}

	public function addFilter(FilterInterface $filter): PropertyDefinition
	{
		if (!$this->filterChain)
		{
			$this->filterChain = new FilterChain();
		}

		$this->filterChain->attach($filter);

		return $this;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): PropertyDefinition
	{
		$this->name = $name;
		return $this;
	}

	public function getLabel(): ?string
	{
		return $this->label;
	}

	public function setLabel(?string $label): PropertyDefinition
	{
		$this->label = $label;
		return $this;
	}

	public function getDefaultValue(): mixed
	{
		return $this->defaultValue;
	}

	public function setDefaultValue(mixed $defaultValue): PropertyDefinition
	{
		$this->defaultValue = $defaultValue;
		return $this;
	}

	public function isRequired(): bool
	{
		return $this->required;
	}

	public function setRequired(bool $required): PropertyDefinition
	{
		$this->required = $required;
		return $this;
	}

	public function getValidatorChain(): ?ValidatorChain
	{
		return $this->validatorChain;
	}

	public function getFilterChain(): ?FilterChain
	{
		return $this->filterChain;
	}

	public function getTransformer(): ?string
	{
		return $this->transformer;
	}

	public function setTransformer(?string $transformer): PropertyDefinition
	{
		$this->transformer = $transformer;
		return $this;
	}

	public function getTransformerExecution(): ?string
	{
		return $this->transformerExecution;
	}

	public function setTransformerExecution(?string $transformerExecution): PropertyDefinition
	{
		$this->transformerExecution = $transformerExecution;
		return $this;
	}
}
