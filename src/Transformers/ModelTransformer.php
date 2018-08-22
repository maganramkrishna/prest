<?php

namespace Prest\Transformers;

use Phalcon\Db\Column;

/**
 * Prest\Transformers\ModelTransformer
 *
 * @package Prest\Transformers
 */
class ModelTransformer extends Transformer
{
    const TYPE_UNKNOWN = 0;
    const TYPE_INTEGER = 1;
    const TYPE_FLOAT = 2;
    const TYPE_DOUBLE = 3;
    const TYPE_BOOLEAN = 4;
    const TYPE_STRING = 5;
    const TYPE_DATE = 6;
    const TYPE_JSON = 7;

    protected $modelClass;

    protected $modelDataTypes;
    protected $modelColumnMap;
    protected $modelAttributes;

    public function transform($item)
    {
        if ($item == null) {
            return null;
        }

        $result = [];
        $keyMap = $this->keyMap();

        foreach ($this->getResponseProperties() as $property) {
            $fieldName = $keyMap[$property] ?? $property;
            if (!property_exists($item, $fieldName)) {
                continue;
            }

            $result[$fieldName] = $this->getFieldValue($item, $property, $fieldName);
        }

        $combinedResult = array_merge($result, $this->additionalFields($item));

        return $combinedResult;
    }

    /**
     * Returns keyMap to be used.
     * Keys are the model properties, values are the response keys
     *
     * @return array
     */
    protected function keyMap()
    {
        return [];
    }

    public function getResponseProperties()
    {
        return array_diff($this->includedProperties(), $this->excludedProperties());
    }

    /**
     * @return array Properties to be included in the response
     */
    protected function includedProperties()
    {
        $attributes = $this->getModelAttributes();
        $columnMap = $this->getModelColumnMap();

        if (!is_array($columnMap)) {
            return $attributes;
        }

        return array_map(function ($attribute) use ($columnMap) {
            return array_key_exists($attribute, $columnMap) ? $columnMap[$attribute] : $attribute;
        }, $attributes);
    }

    protected function getModelAttributes()
    {
        if (!$this->modelAttributes) {
            $modelClass = $this->getModelClass();

            $this->modelAttributes = $this->modelsMetadata->getAttributes(new $modelClass);
        }

        return $this->modelAttributes;
    }

    public function getModelClass()
    {
        return $this->modelClass;
    }

    public function setModelClass($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    protected function getModelColumnMap()
    {
        if (!$this->modelColumnMap) {
            $modelClass = $this->getModelClass();
            $metaDataColumnMap = $this->modelsMetadata->getColumnMap(new $modelClass);

            $this->modelColumnMap = array_merge($metaDataColumnMap ? $metaDataColumnMap : [], $this->keyMap());
        }

        return $this->modelColumnMap;
    }

    /**
     * @return array Properties to be excluded in the response
     */
    protected function excludedProperties()
    {
        return [];
    }

    protected function getFieldValue($item, $propertyName, $fieldName)
    {
        $modelDataTypes = $this->getModelDataTypes();
        $dataType = $modelDataTypes[$propertyName] ?? null;

        $model = $this->getModel($item);
        $value = $model->$propertyName;

        if ($value === null || empty($dataType)) {
            return $value;
        }

        switch ($dataType) {
            case self::TYPE_INTEGER:
                return $this->formatHelper->int($value);
            case self::TYPE_FLOAT:
                return $this->formatHelper->float($value);
            case self::TYPE_DOUBLE:
                return $this->formatHelper->double($value);
            case self::TYPE_BOOLEAN:
                return $this->formatHelper->bool($value);
            case self::TYPE_STRING:
                return (string)$value;
            case self::TYPE_DATE:
                return $this->formatHelper->date($value);
            case self::TYPE_JSON:
                return json_decode($value);
        }

        return $value;
    }

    public function getModelDataTypes()
    {
        if (!$this->modelDataTypes) {
            $modelClass = $this->getModelClass();
            $columnMap = $this->getModelColumnMap();

            $dataTypes = $this->modelsMetadata->getDataTypes(new $modelClass);

            $mappedDataTypes = $dataTypes;

            if (is_array($columnMap)) {
                $mappedDataTypes = [];

                foreach ($dataTypes as $attributeName => $dataType) {
                    $mappedAttributeName = $columnMap[$attributeName] ?? $attributeName;
                    $mappedDataTypes[$mappedAttributeName] = $this->getMappedDatabaseType($dataType);
                }
            }

            $this->modelDataTypes = array_merge($mappedDataTypes, $this->typeMap());
        }

        return $this->modelDataTypes;
    }

    protected function getMappedDatabaseType($type)
    {
        $responseType = null;

        switch ($type) {
            case Column::TYPE_INTEGER:
            case Column::TYPE_BIGINTEGER:
                $responseType = self::TYPE_INTEGER;
                break;
            case Column::TYPE_DECIMAL:
            case Column::TYPE_FLOAT:
                $responseType = self::TYPE_FLOAT;
                break;
            case Column::TYPE_DOUBLE:
                $responseType = self::TYPE_DOUBLE;
                break;
            case Column::TYPE_BOOLEAN:
                $responseType = self::TYPE_BOOLEAN;
                break;
            case Column::TYPE_VARCHAR:
            case Column::TYPE_CHAR:
            case Column::TYPE_TEXT:
            case Column::TYPE_BLOB:
            case Column::TYPE_MEDIUMBLOB:
            case Column::TYPE_LONGBLOB:
                $responseType = self::TYPE_STRING;
                break;
            case Column::TYPE_DATE:
            case Column::TYPE_DATETIME:
                $responseType = self::TYPE_DATE;
                break;
            case Column::TYPE_JSON:
            case Column::TYPE_JSONB:
                $responseType = self::TYPE_JSON;
                break;
            default:
                $responseType = self::TYPE_STRING;
        }

        return $responseType;
    }

    protected function typeMap()
    {
        return [];
    }

    protected function getModel($item)
    {
        return $item;
    }

    protected function additionalFields($item)
    {
        return [];
    }
}
