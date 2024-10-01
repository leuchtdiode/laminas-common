<?php
declare(strict_types=1);

namespace Common\Dto\Save;

use Common\Dto\PropertyConfig;
use Common\RequestData\Transformer\Boolean;
use Doctrine\ORM\EntityManager;
use Laminas\I18n\Validator\DateTime as DateTimeValidator;
use Laminas\Validator\Digits;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;
use Laminas\Validator\Uuid;

class Validator
{
	const DEFAULT_STRING_LENGTH = 255;

	public function __construct(
		private readonly EntityManager $entityManager
	)
	{
	}

	public function validate(ValidationParams $params): ValidationResult
	{
		$validationResult = new ValidationResult();

		$classMetadata  = $params->getClassMetadata();
		$property       = $params->getProperty();
		$value          = $params->getValue();
		$propertyConfig = $property->getAttributes(PropertyConfig::class)[0] ?? null;

		$validators = [];

		if ($classMetadata->hasAssociation($property->getName()))
		{
			if ($value && !is_string($value))
			{
				return $validationResult;
			}

			$associationMapping = $classMetadata->getAssociationMapping($property->getName());

			$fieldRequired = !($associationMapping['nullable'] ?? true);

			$classMetadataTargetEntity = $this->entityManager->getClassMetadata(
				$associationMapping['targetEntity']
			);

			$targetFieldMapping = $classMetadataTargetEntity->getFieldMapping('id');

			$fieldType = $targetFieldMapping['type'];
		}
		else
		{
			$fieldMapping  = $classMetadata->getFieldMapping($property->getName());
			$fieldType     = $fieldMapping['type'] ?? null;
			$fieldRequired = $fieldMapping['required'] ?? false;

			if ($value && $propertyConfig)
			{
				$validators = $propertyConfig->getArguments()['validators'] ?? [];
			}
		}

		if ($fieldRequired)
		{
			$validators[] = new NotEmpty();
		}

		if ($fieldType === 'string' && $value)
		{
			$validators[] = new StringLength([ 'max' => $fieldMapping['length'] ?? self::DEFAULT_STRING_LENGTH ]);
		}

		if ($fieldType === 'uuid' && $value)
		{
			$validators[] = new Uuid();
		}

		if ($fieldType === 'carbon_immutable' && $value)
		{
			$validators[] = new DateTimeValidator();
		}

		if ($fieldType === 'carbondatetime_immutable' && $value)
		{
			$validators[] = new DateTimeValidator([ 'pattern' => 'Y-m-d H:i:s' ]);
		}

		foreach ($validators as $validator)
		{
			$valid = $validator->isValid($value);

			if (!$valid)
			{
				foreach ($validator->getMessages() as $message)
				{
					if (($label = $propertyConfig?->getArguments()['validationLabel'] ?? null))
					{
						$message = sprintf('%s: %s', $label, $message);
					}

					$validationResult->addError(
						PropertyInvalidError::create($message)
					);
				}
			}
		}

		return $validationResult;
	}
}
