<?php

namespace Prest\Mvc\Controllers;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\ModelInterface;
use Prest\Api\Resource;
use Prest\Constants\ErrorCodes;
use Prest\Mvc\Hooks\HandleTrait;
use Prest\Exception\DomainException;
use Prest\Constants\PostedDataMethods;
use Prest\Mvc\Model\FillableInterface;
use Phalcon\Mvc\Model\Query\BuilderInterface;

/**
 * Prest\Mvc\Controllers\CrudResourceController
 *
 * @property \Phalcon\Mvc\Model\Manager|\Phalcon\Mvc\Model\ManagerInterface $modelsManager
 *
 * @package Prest\Mvc\Controllers
 */
class CrudResourceController extends ResourceController
{
    use HandleTrait;

    public function all()
    {
        $this->beforeHandle();
        $this->beforeHandleRead();
        $this->beforeHandleAll();

        $data = $this->getAllData();

        if (!$this->allAllowed($data)) {
            return $this->onNotAllowed();
        }

        $response = $this->getAllResponse($data);

        $this->afterHandleAll($data, $response);
        $this->afterHandleRead();
        $this->afterHandle();

        return $response;
    }

    protected function getAllData()
    {
        $phqlBuilder = $this->phqlQueryParser->fromQuery($this->query, $this->getResource());

        $this->modifyReadQuery($phqlBuilder);
        $this->modifyAllQuery($phqlBuilder);

        return $phqlBuilder->getQuery()->execute();
    }

    protected function modifyReadQuery(BuilderInterface $query)
    {
    }

    protected function modifyAllQuery(BuilderInterface $query)
    {
    }

    protected function allAllowed($data)
    {
        return true;
    }

    /**
     * @throws DomainException
     */
    protected function onNotAllowed()
    {
        throw new DomainException(
            ErrorCodes::ACCESS_DENIED,
            'Operation is not allowed'
        );
    }

    protected function getAllResponse($data)
    {
        return $this->createResourceCollectionResponse($data);
    }

    public function find($identity)
    {
        $this->beforeHandle();
        $this->beforeHandleRead();
        $this->beforeHandleFind($identity);

        $item = $this->getFindData($identity);

        if (!$item) {
            return $this->onItemNotFound($identity);
        }

        if (!$this->findAllowed($identity, $item)) {
            return $this->onNotAllowed();
        }

        $response = $this->getFindResponse($item);

        $this->afterHandleFind($item, $response);
        $this->afterHandleRead();
        $this->afterHandle();

        return $response;
    }

    /**
     * Tries to fetch entity.
     *
     * @param $identity
     * @return ModelInterface|FillableInterface|null
     */
    protected function getFindData($identity)
    {
        $resource = $this->getResource();
        if (!$resource instanceof Resource) {
            return null;
        }

        $phqlBuilder = $this->phqlQueryParser
            ->fromQuery($this->query, $resource)
            ->andWhere(
                "[{$resource->getModel()}].{$resource->getModelPrimaryKey()}  = :id:",
                ['id' => $identity]
            )
            ->limit(1);

        $this->modifyReadQuery($phqlBuilder);
        $this->modifyFindQuery($phqlBuilder, $identity);

        $results = $phqlBuilder->getQuery()->execute();

        return count($results) >= 1 ? $results->getFirst() : null;
    }

    protected function getModelPrimaryKey()
    {
        return $this->getResource()->getModelPrimaryKey();
    }

    protected function modifyFindQuery(BuilderInterface $query, $identity)
    {
    }

    /**
     * @param mixed $identity
     * @throws DomainException
     */
    protected function onItemNotFound($identity)
    {
        throw new DomainException(
            ErrorCodes::DATA_NOT_FOUND,
            'Item was not found', ['id' => $identity]
        );
    }

    protected function findAllowed($identity, $item)
    {
        return true;
    }

    protected function getFindResponse($item)
    {
        return $this->createResourceResponse($item);
    }

    public function create()
    {
        $this->beforeHandle();
        $this->beforeHandleWrite();
        $this->beforeHandleCreate();

        $data = $this->getPostedData();

        if (!$data || count($data) == 0) {
            return $this->onNoDataProvided();
        }

        if (!$this->postDataValid($data, false)) {
            return $this->onDataInvalid($data);
        }

        if (!$this->saveAllowed($data) || !$this->createAllowed($data)) {
            return $this->onNotAllowed();
        }

        $data = $this->transformPostData($data);

        $item = $this->createModelInstance();

        $newItem = $this->createItem($item, $data);

        if (!$newItem) {
            return $this->onCreateFailed($item, $data);
        }

        $primaryKey = $this->getModelPrimaryKey();
        $responseData = $this->getFindData($newItem->$primaryKey);

        $response = $this->getCreateResponse($responseData, $data);

        $this->afterHandleCreate($newItem, $data, $response);
        $this->afterHandleWrite();
        $this->afterHandle();

        return $response;
    }

    protected function getPostedData()
    {
        $resourcePostedDataMode = $this->getResource()->getPostedDataMethod();
        $endpointPostedDataMode = $this->getEndpoint()->getPostedDataMethod();

        $postedDataMode = $resourcePostedDataMode;
        if ($endpointPostedDataMode != PostedDataMethods::AUTO) {
            $postedDataMode = $endpointPostedDataMode;
        }

        $postedData = null;

        switch ($postedDataMode) {
            case PostedDataMethods::POST:
                $postedData = $this->request->getPost();
                break;
            case PostedDataMethods::JSON_BODY:
                $postedData = $this->request->getJsonRawBody(true);
                break;
            case PostedDataMethods::AUTO:
            default:
                $postedData = $this->request->getPostedData($this->getEndpoint()->getHttpMethod());
        }

        return $postedData;
    }

    /**
     * @throws DomainException
     */
    protected function onNoDataProvided()
    {
        throw new DomainException(
            ErrorCodes::POST_DATA_NOT_PROVIDED,
            'No post-data provided'
        );
    }

    /**
     * @param $data
     * @param $isUpdate
     * @return bool
     */
    protected function postDataValid($data, $isUpdate)
    {
        return true;
    }

    /**
     * @param $data
     * @throws DomainException
     */
    protected function onDataInvalid($data)
    {
        throw new DomainException(
            ErrorCodes::POST_DATA_INVALID,
            'Post-data is invalid', ['data' => $data]
        );
    }

    protected function saveAllowed($data)
    {
        return true;
    }

    protected function createAllowed($data)
    {
        return true;
    }

    protected function transformPostData($data)
    {
        $result = [];

        foreach ($data as $key => $value) {
            $result[$key] = $this->transformPostDataValue($key, $value, $data);
        }

        return $result;
    }

    protected function transformPostDataValue($key, $value, $data)
    {
        return $value;
    }

    /**
     * @return Model
     */
    protected function createModelInstance()
    {
        $modelClass = $this->getResource()->getModel();

        /** @var Model $item */
        $item = new $modelClass();

        return $item;
    }

    /**
     * Assigns values to a model from an array, save model to the database and call corresponding hooks.
     *
     * @param ModelInterface $item
     * @param array $data
     *
     * @return ModelInterface|null
     */
    protected function createItem(ModelInterface $item, array $data)
    {
        $this->beforeAssignData($item, $data);

        $whitelist = $this->whitelistCreate();
        if (empty($whitelist) && $item instanceof FillableInterface) {
            $whitelist = $item->whitelist();
        }

        $item->assign($data, null, $whitelist);
        $this->afterAssignData($item, $data);

        $this->beforeSave($item);
        $this->beforeCreate($item);

        $success = $item->create();

        if ($success) {
            $this->afterCreate($item);
            $this->afterSave($item);
        }

        return $success ? $item : null;
    }

    protected function beforeAssignData(ModelInterface $item, $data)
    {
    }

    protected function afterAssignData(ModelInterface $item, $data)
    {
    }

    protected function beforeSave(ModelInterface $item)
    {
    }

    protected function beforeCreate(ModelInterface $item)
    {
    }

    protected function afterCreate(ModelInterface $item)
    {
    }

    protected function afterSave(ModelInterface $item)
    {
    }

    /**
     * @param Model $item
     * @param $data
     * @throws DomainException
     */
    protected function onCreateFailed(Model $item, $data)
    {
        throw new DomainException(ErrorCodes::DATA_FAILED, 'Unable to create item', [
            'messages' => $this->getMessages($item->getMessages()),
            'data' => $data,
            'item' => $item->toArray()
        ]);
    }

    private function getMessages($messages)
    {
        $messages = isset($messages) ? $messages : [];

        return array_map(function (Model\Message $message) {
            return $message->getMessage();
        }, $messages);
    }

    protected function getCreateResponse($createdItem, $data)
    {
        return $this->createResourceOkResponse($createdItem);
    }

    public function update($identity)
    {
        $this->beforeHandle();
        $this->beforeHandleWrite();
        $this->beforeHandleUpdate($identity);

        $data = $this->getPostedData();
        $item = $this->getItem($identity);

        if (!$item) {
            return $this->onItemNotFound($identity);
        }

        if (!$data || count($data) == 0) {
            return $this->onNoDataProvided();
        }

        if (!$this->postDataValid($data, true)) {
            return $this->onDataInvalid($data);
        }

        if (!$this->saveAllowed($data) || !$this->updateAllowed($item, $data)) {
            return $this->onNotAllowed();
        }

        $data = $this->transformPostData($data);

        $newItem = $this->updateItem($item, $data);

        if (!$newItem) {
            return $this->onUpdateFailed($item, $data);
        }

        $primaryKey = $this->getModelPrimaryKey();
        $responseData = $this->getFindData($newItem->$primaryKey);

        $response = $this->getUpdateResponse($responseData, $data);

        $this->afterHandleUpdate($newItem, $data, $response);
        $this->afterHandleWrite();
        $this->afterHandle();

        return $response;
    }

    /**
     * Tries to get entity.
     *
     * @param mixed $identity Model identity
     * @return ModelInterface|FillableInterface|null
     */
    protected function getItem($identity)
    {
        if (!$resource = $this->getResource()) {
            return null;
        }

        $modelClass = $resource->getModel();

        $entity = $this->modelsManager
            ->createBuilder([$identity])
            ->from($modelClass)
            ->limit(1)
            ->getQuery()
            ->setUniqueRow(true)
            ->execute();

        // Do not return weird false
        return $entity ?: null;
    }

    protected function updateAllowed(Model $item, $data)
    {
        return true;
    }

    protected function whitelist()
    {
        return null;
    }

    protected function whitelistCreate()
    {
        return $this->whitelist();
    }

    protected function whitelistUpdate()
    {
        return $this->whitelist();
    }

    /**
     * @param Model $item
     * @param $data
     *
     * @return Model Updated model
     */
    protected function updateItem(Model $item, $data)
    {
        $this->beforeAssignData($item, $data);

        $whitelist = $this->whitelistCreate();
        if (empty($whitelist) && $item instanceof FillableInterface) {
            $whitelist = $item->whitelist();
        }

        $item->assign($data, null, $whitelist);
        $this->afterAssignData($item, $data);

        $this->beforeSave($item);
        $this->beforeUpdate($item);

        $success = $item->update();

        if ($success) {
            $this->afterUpdate($item);
            $this->afterSave($item);
        }

        return $success ? $item : null;
    }

    protected function beforeUpdate(Model $item)
    {
    }

    protected function afterUpdate(Model $item)
    {
    }

    /**
     * @param Model $item
     * @param $data
     * @throws DomainException
     */
    protected function onUpdateFailed(Model $item, $data)
    {
        throw new DomainException(ErrorCodes::DATA_FAILED, 'Unable to update item', [
            'messages' => $this->getMessages($item->getMessages()),
            'data' => $data,
            'item' => $item->toArray()
        ]);
    }

    protected function getUpdateResponse($updatedItem, $data)
    {
        return $this->createResourceOkResponse($updatedItem);
    }

    public function remove($identity)
    {
        $this->beforeHandle();
        $this->beforeHandleWrite();
        $this->beforeHandleRemove($identity);

        $item = $this->getItem($identity);

        if (!$item) {
            return $this->onItemNotFound($identity);
        }

        if (!$this->removeAllowed($item)) {
            return $this->onNotAllowed();
        }

        $success = $this->removeItem($item);

        if (!$success) {
            return $this->onRemoveFailed($item);
        }

        $response = $this->getRemoveResponse($item);

        $this->afterHandleRemove($item, $response);
        $this->afterHandleWrite();
        $this->afterHandle();

        return $response;
    }

    protected function removeAllowed(Model $item)
    {
        return true;
    }

    /**
     * @param Model $item
     *
     * @return bool Remove succeeded/failed
     */
    protected function removeItem(Model $item)
    {
        $this->beforeRemove($item);

        $success = $item->delete();

        if ($success) {
            $this->afterRemove($item);
        }

        return $success;
    }

    protected function beforeRemove(Model $item)
    {
    }

    protected function afterRemove(Model $item)
    {
    }

    /**
     * @param Model $item
     * @throws DomainException
     */
    protected function onRemoveFailed(Model $item)
    {
        throw new DomainException(ErrorCodes::DATA_FAILED, 'Unable to remove item', [
            'messages' => $this->getMessages($item->getMessages()),
            'item' => $item->toArray()
        ]);
    }

    protected function getRemoveResponse(Model $removedItem)
    {
        return $this->createOkResponse();
    }
}
