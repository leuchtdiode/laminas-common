<?php
declare(strict_types=1);

namespace Common\Dto;

use Common\Db\EntityRepository;
use Common\Dto\Save\HandleItemParams;
use Common\Dto\Save\Transformer;
use Common\Dto\Save\TransformParams;
use Common\Dto\Save\Validator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\PersistentCollection;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Throwable;

abstract class BaseSaver
{
	abstract protected function getKey(): string;

	public function __construct(
		private readonly ContainerInterface $container,
		private readonly EntityManager $entityManager,
		private readonly KeyConfig $keyConfig,
		private readonly DefaultMapper $defaultMapper,
		private readonly Validator $validator,
		private readonly Transformer $transformer
	)
	{
	}

	/**
	 * @throws Throwable
	 */
	public function save(BaseSaveParams $params): BaseSaveResult
	{
		$result = new BaseSaveResult();
		$result->setSuccess(false);

		$saveTransaction = new Save\Transaction();

		$firstHandleItemResult = $this->handleItem(
			HandleItemParams::create()
				->setTransaction($saveTransaction)
				->setDtoId($params->getDtoId())
				->setData($params->getData())
		);

		if ($saveTransaction->hasValidationErrors())
		{
			$result->setErrors($saveTransaction->getValidationErrors());
			return $result;
		}

		$entity = $firstHandleItemResult->getEntity();

		foreach ($saveTransaction->getEntities() as $saveTransactionEntity)
		{
			$this->entityManager->persist($saveTransactionEntity);
		}

		if ($params->isFlush())
		{
			$this->entityManager->flush();
		}

		$result->setSuccess(true);
		$result->setDto(
			$this->defaultMapper->map(
				DefaultMapParams::create()
					->setEntity($entity)
			)
		);

		return $result;
	}

	/**
	 * @throws Throwable
	 */
	private function handleItem(Save\HandleItemParams $params): Save\HandleItemResult
	{
		$handleItemResult = new Save\HandleItemResult();

		$transaction = $params->getTransaction();

		$dbNamespace = $this->keyConfig->getDbNamespace($params->getDtoKey() ?? $this->getKey());

		/**
		 * @var EntityRepository $repository
		 */
		$repository = $this->container->get($dbNamespace . '\Repository');

		$entityClass = $dbNamespace . '\Entity';

		$data  = $params->getData();
		$dtoId = $params->getDtoId();

		$entity = $dtoId
			? $repository->find($dtoId)
			: new $entityClass();

		$reflection = new ReflectionClass($entityClass);

		$result = new BaseSaveResult();
		$result->setSuccess(false);

		$classMetaData = $this->entityManager->getClassMetadata($entityClass);

		// TODO validate mandatory properties as well, even if they are non existing in the data payload (only if addition)

		foreach ($data->getData() as $property => $value)
		{
			// TODO auto trim

			// do not fail if property is sent which does not exist
			try
			{
				$property = $reflection->getProperty($property);
			}
			catch (Throwable $t)
			{
				continue;
			}

			$value = $this->transformer->transformPreValidation(
				TransformParams::create()
					->setClassMetadata($classMetaData)
					->setProperty($property)
					->setValue($value)
			);

			$propertyTypeClass = $property
				->getType()
				->getName();

			$validationResult = $this->validator->validate(
				Save\ValidationParams::create()
					->setClassMetadata($classMetaData)
					->setProperty($property)
					->setValue($value)
			);

			if ($validationResult->hasErrors())
			{
				$transaction->addValidationErrors($validationResult->getErrors());

				continue;
			}

			$value = $this->transformer->transform(
				TransformParams::create()
					->setClassMetadata($classMetaData)
					->setProperty($property)
					->setValue($value)
			);

			$setter = 'set' . ucfirst($property->getName());

			if ($value !== null && $classMetaData->hasAssociation($property->getName()))
			{
				$assiociationMapping = $classMetaData->getAssociationMapping($property->getName());

				$targetEntityClass = $assiociationMapping['targetEntity'];

				if (!is_array($value)) // ID or DTO given, just set entity
				{
					$valueId = $value instanceof Dto
						? $value->getId()
						: $value;

					$entity->{$setter}(
						$this->entityManager
							->getRepository($targetEntityClass)
							->find($valueId)
					);
				}
				else // object, recursiveness
				{
					$associationType = $assiociationMapping['type'];

					$targetReflectionClass = new ReflectionClass($targetEntityClass);

					if (
						$propertyTypeClass === Collection::class
						|| $propertyTypeClass === PersistentCollection::class
						|| $propertyTypeClass === ArrayCollection::class
					)
					{
						$getter = 'get' . ucfirst($property->getName());

						// use new collection if all values are new, because otherwise we have duplicates
						$collection = $this->allValuesNew($value)
							? new ArrayCollection()
							: $entity->{$getter}();

						foreach ($value as $v)
						{
							$vId = $v['id'] ?? null;

							$handleItemResult = $this->handleItem(
								HandleItemParams::create()
									->setDtoId(
										$vId
											? (string)$vId
											: null
									)
									->setDtoKey(
										$targetReflectionClass->getAttributes(EntityConfig::class)[0]->getArguments()['dtoKey']
									)
									->setData(
										BaseSaveData::fromArray(
											$this->arrayWithoutId($v)
										)
									)
									->setTransaction($transaction)
							);

							$itemSetter = 'set' . ucfirst($assiociationMapping['mappedBy']);

							$handleItemResultEntity = $handleItemResult->getEntity();
							$handleItemResultEntity->{$itemSetter}($entity);

							$collection->add($handleItemResultEntity);
						}

						$entity->{$setter}($collection);
					}
					else
					{
						$valueId = $value['id'] ?? null;

						$handleItemResult = $this->handleItem(
							HandleItemParams::create()
								->setDtoId(
									$valueId
										? (string)$valueId
										: null
								)
								->setDtoKey(
									$targetReflectionClass->getAttributes(EntityConfig::class)[0]->getArguments()['dtoKey']
								)
								->setData(
									BaseSaveData::fromArray(
										$this->arrayWithoutId($value)
									)
								)
								->setTransaction($transaction)
						);

						$entity->{$setter}($handleItemResult->getEntity());
					}
				}
			}
			else
			{
				$entity->{$setter}($value);
			}
		}

		$handleItemResult->setEntity($entity);

		$transaction->addEntity($entity);

		return $handleItemResult;
	}

	private function allValuesNew(array $values): bool
	{
		$withoutId = array_filter(
			$values,
			fn(array $item) => empty($item['id'] ?? null)
		);

		return count($withoutId) === count($values);
	}

	private function arrayWithoutId(array $array): array
	{
		if (array_key_exists('id', $array))
		{
			unset($array['id']);
		}

		return $array;
	}
}
