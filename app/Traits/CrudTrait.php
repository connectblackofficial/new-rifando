<?php

namespace App\Traits;

use App\Exceptions\UserErrorException;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

trait CrudTrait
{

    private $modelName;
    private $modelClass;
    private $crudData = null;
    private $group = null;
    private $pagination = 25;
    private $advancedFields = [];
    private $formatFieldsFn = [];
    private $modelEnumFiels = [];
    private $orderByCols = [];
    private $orderByColsFormated;
    private $modelFields;
    private $alloweds = ['edit' => true, 'create' => true, 'list' => true, 'destroy' => true, 'show' => true];
    private $pkModelCol;

    private $modelInstance;
    private $removedFromAdvancedSearch = [];
    private $hashFields = ['password'];
    private $uniqueFields = ['email'];

    private function getModelFields()
    {
        if (is_null($this->modelFields)) {
            /** @var Model $modelInstance */
            $modelInstance = $this->getModelInstance();
            $this->modelFields = $modelInstance->getFillable();
            $this->pkModelCol = $modelInstance->getKeyName();
        }
        return $this->modelFields;
    }

    private function getModelInstance()
    {
        if (is_null($this->modelInstance)) {
            /** @var Model $modelInstance */
            $this->modelInstance = new $this->modelClass;
        }
        return $this->modelInstance;
    }


    private function getPkModelCol()
    {
        if (is_null($this->pkModelCol)) {
            /** @var Model $modelInstance */
            $modelInstance = $this->getModelInstance();
            $this->pkModelCol = $modelInstance->getKeyName();
        }
        return $this->pkModelCol;
    }

    private function hasPermission($action)
    {
        if (isset($this->alloweds[$action]) && $this->alloweds[$action] === true) {
            return true;
        }
        return false;
    }

    public function denyEdit()
    {
        $this->alloweds['edit'] = false;
    }

    public function denyCreate()
    {
        $this->alloweds['create'] = false;
    }

    public function denyList()
    {
        $this->alloweds['list'] = false;
    }

    public function denyDestroy()
    {
        $this->alloweds['destroy'] = false;
    }

    public function denyShow()
    {
        $this->alloweds['show'] = false;
    }


    private function hasPermissionOrFail($action)
    {
        if (!$this->hasPermission($action)) {
            throw UserErrorException::unauthorizedAccess();
        }

    }

    private function getModelEumFiels()
    {
        if (count($this->modelEnumFiels) == 0) {
            $this->modelEnumFiels = $this->modelClass::getEnumFields();
        }
        return $this->modelEnumFiels;
    }

    private function getOrderByCols()
    {
        if (is_null($this->orderByColsFormated)) {
            if (count($this->orderByCols) == 0) {
                $fillAble = $this->getModelFields();
                $this->orderByCols = $fillAble;
                $denyList = ['password', 'updated_at'];
                foreach ($this->modelClass::getEnumFields() as $fName => $fValue) {
                    $denyList[] = $fName;
                }
                foreach ($this->orderByCols as $k => $v) {
                    if (in_array($v, $denyList)) {
                        unset($this->orderByCols[$k]);
                    }
                }
            }
            $cols = [];
            foreach ($this->orderByCols as $c => $v) {
                $cols[$c] = $v;
            }
            $this->orderByColsFormated = $cols;

        }
        return $this->orderByColsFormated;
    }

    private function getCrudData()
    {
        if (is_null($this->crudData)) {
            $routeGroup = $this->getGroup();
            $viewName = $this->crudName;
            $this->crudData = [
                'routeEdit' => "{$routeGroup}.{$viewName}.edit",
                'routeIndex' => "{$routeGroup}.{$viewName}.index",
                'routeDelete' => "{$routeGroup}.{$viewName}.destroy",
                'routeShow' => "{$routeGroup}.{$viewName}.show",
                "routeView" => "{$routeGroup}.{$viewName}.show",
                "routeCreate" => "{$routeGroup}.{$viewName}.create",
                "routeStore" => "{$routeGroup}.{$viewName}.store",
                'routeUpdate' => "{$routeGroup}.{$viewName}.update",
                'formatFieldsFn' => $this->formatFieldsFn(),
                'advancedFields' => $this->advancedSearchFields(),
                'baseLang' => strtolower($this->getModelName()),
                'getDataFromRequest' => request()->query(),
                'orderByCols' => $this->getOrderByCols(),
                'permissions' => $this->alloweds,
                'crudNameSingular' => $this->crudNameSingular,
                'modelFields' => $this->getModelFields(),
                'pkModelCol' => $this->getPkModelCol()
            ];
        }
        return $this->crudData;
    }

    private function formatFieldsFn()
    {
        foreach ($this->getModelEumFiels() as $fName => $field) {
            $this->formatFieldsFn[$fName] = function ($value) use ($field) {
                if (isset($field[$value])) {
                    return htmlLabel($field[$value]);
                }
                return "";
            };
        }
        return $this->formatFieldsFn;
    }

    private function getRouteIndex()
    {
        $crudData = $this->getCrudData();
        return $crudData['routeIndex'];
    }

    private function getGroup()
    {
        if (is_null($this->group)) {
            $this->group = str_replace("/", "", $this->routeGroup);
        }
        return $this->group;
    }

    private function getRouteCreate()
    {
        $crudData = $this->getCrudData();
        return $crudData['routeCreate'];
    }

    private function getRouteStore()
    {
        $crudData = $this->getCrudData();
        return $crudData['routeStore'];
    }

    private function getRouteShow()
    {
        $crudData = $this->getCrudData();
        return $crudData['routeShow'];
    }

    private function getRouteEdit()
    {
        $crudData = $this->getCrudData();
        return $crudData['routeEdit'];
    }

    private function getRouteUpdate()
    {
        $crudData = $this->getCrudData();
        return $crudData['routeUpdate'];
    }

    private function getRouteDelete()
    {
        $crudData = $this->getCrudData();
        return $crudData['routeDelete'];
    }

    private function getModelName()
    {
        if (is_null($this->modelName)) {
            $this->modelName = ucfirst($this->crudNameSingular);
        }
        return $this->modelName;
    }

    private function parseViewCi($view, $pageData = [])
    {
        if (isset($pageData[$this->crudName])) {
            $pageData['rows'] = $pageData[$this->crudName];
        }
        if (isset($pageData[$this->crudNameSingular])) {
            $pageData['row'] = $pageData[$this->crudNameSingular];
        }
        $crudData = $this->getCrudData();
        $pageData = array_merge($pageData, $crudData);
        return view($view, $pageData);
    }

    private function beforeDestroy($id)
    {

    }

    private function afterDestroy($id)
    {

    }

    private function beforeRenderEditPage($id)
    {

    }

    private function beforeRenderShowPage($id)
    {

    }

    public function beforeRenderCreatePage()
    {

    }

    public function beforeUpdate($requestData, $id)
    {
        return $requestData;
    }

    public function afterUpdate($id)
    {

    }

    public function beforeStore($requestData)
    {
        return $requestData;
    }

    public function afterStore()
    {

    }

    public function destroy($id)
    {
        $currentRow = $this->modelClass::find($id);
        $destroyFn = function (self $instance) use ($id, $currentRow) {
            if ($currentRow instanceof User && $currentRow->id == \Auth::user()->id) {
                throw new UserErrorException(htmlLabel("it is not possible to delete your own account."));
            }
            $instance->hasPermissionOrFail('destroy');
            if ($instance->alloweds['destroy'] === false) {
                throw  UserErrorException::unauthorizedAccess();
            }
            $instance->beforeDestroy($id);
            $instance->modelClass::destroy($id);
            $instance->afterDestroy($id);
            return redirect(route($this->getRouteIndex()))->with('success', htmlLabel($this->crudNameSingular . ' deleted'));
        };
        return $this->processResponse($destroyFn);
    }


    private function processUpdate($validationRule, array $requestData, $id)
    {
        $currentData = $this->modelClass::findOrFail($id);


        $processUpdateFn = function (self $instance) use ($requestData, $id, $currentData) {
            $instance->hasPermissionOrFail('edit');
            $currentData = convertToArray($currentData);
            foreach ($currentData as $k => $v) {
                $areEquals = isset($currentData[$v]) && isset($requestData[$v]) && $currentData[$v] == $requestData[$v];
                if (!in_array($k, $this->hashFields) && $areEquals) {
                    unset($requestData[$v]);
                }
            }
            $requestData = $instance->parseHashFields($requestData);
            $requestData = $instance->beforeUpdate($requestData, $id);
            $currentData->update($requestData);
            $instance->afterUpdate($id);
            return redirect(route($instance->getRouteIndex()))->with('success', htmlLabel($instance->crudNameSingular . ' updated'));
        };
        return $this->processResponse($processUpdateFn);
    }

    public function canIgnoreField($k, $currentData, $requestData)
    {
        return !in_array($k, $this->hashFields) && isset($currentData[$k]) && isset($requestData[$k]) && $currentData[$k] == $requestData[$k];
    }

    public function getCleanRulesAndData($class, $currentData, $requestData)
    {
        $rules = (new $class)->rules();
        foreach ($requestData as $k => $v) {
            if ($this->canIgnoreField($k, $currentData, $requestData)) {
                unset($rules[$k]);
                unset($requestData[$k]);
            }
        }
        return ['rules'=>$rules,'requestData'=>$requestData];
    }

    private function parseHashFields($requestData)
    {
        foreach ($this->hashFields as $hashField) {
            if (isset($requestData[$hashField]) && !empty($requestData[$hashField])) {
                $requestData[$hashField] = Hash::make($requestData[$hashField]);
            } else if (isset($requestData[$hashField])) {
                unset($requestData[$hashField]);
            }
        }
        return $requestData;
    }

    private function processStore(array $requestData)
    {
        $processStoreFn = function (self $instance) use ($requestData) {
            $instance->hasPermissionOrFail('create');

            $requestData = $instance->parseHashFields($requestData);
            $requestData = $instance->beforeStore($requestData);
            $fillable = (new $this->modelClass)->getFillable();

            if (in_array('user_id', $fillable)) {
                $requestData['user_id'] = getSiteOwnerId();
            }
            $this->modelClass::create($requestData);
            $instance->afterStore();
            return redirect(route($instance->getRouteIndex()))->with('success', htmlLabel($instance->crudNameSingular . ' added'));
        };
        return $this->processResponse($processStoreFn);
    }

    private function advancedSearchFields()
    {
        if (count($this->advancedFields) == 0) {
            $modelFields = $this->getModelFields();
            $fields = [];
            $orderFields = [];
            $orderByColsOptions = [];
            $orderByCols = $this->getOrderByCols();
            foreach ($orderByCols as $c) {
                $orderByColsOptions[$c] = $c;
            }
            $orderFields['orderCol'] = ['type' => 'select', 'options' => $orderByColsOptions];
            $orderFields['orderType'] = ['type' => 'select', 'options' => ['asc' => 'crescent', 'desc' => "decrescent"]];
            foreach ($orderFields as $fName => $fData) {
                if (in_array($fName, $this->removedFromAdvancedSearch)) {
                    continue;
                }
                $fields[$fName] = $fData;
            }
            foreach ($modelFields as $fName) {
                if (in_array($fName, $this->removedFromAdvancedSearch)) {
                    continue;
                }
                if ($fName == 'created_at') {
                    $fields['from_date'] = ['type' => 'date'];
                    $fields['to_date'] = ['type' => 'date'];
                } else {
                    $fields[$fName] = ['type' => 'text'];
                }
            }
            foreach ($this->getModelEumFiels() as $fName => $options) {
                if (in_array($fName, $this->removedFromAdvancedSearch)) {
                    continue;
                }
                $fields[$fName] = ['type' => 'select', 'options' => $options];
            }


            $this->advancedFields = $fields;
        }
        return $this->advancedFields;
    }

    private function hasSearchFields(Request $request)
    {

        $requestData = $request->query();
        if (!empty($requestData['search'])) {
            return true;
        } else {
            foreach ($this->advancedSearchFields() as $field => $fData) {
                if (isset($requestData[$field]) && !empty($requestData[$field])) {
                    return true;
                }
            }
        }

        return false;
    }

    public function index(Request $request)
    {
        $showPageIndex = function (self $instance) use ($request) {
            $instance->hasPermissionOrFail('list');

            $perPage = $instance->pagination;
            $requestData = $request->query();
            if ($instance->hasSearchFields($request)) {
                $orderCol = $instance->getPkModelCol();
                $orderDir = "desc";
                if (isset($requestData['orderCol']) && !empty($requestData['orderCol']) && in_array($requestData['orderCol'], $this->getOrderByCols())) {
                    $orderCol = $requestData['orderCol'];
                }
                if (isset($requestData['orderType']) && !empty($requestData['orderType']) && in_array($requestData['orderType'], ['desc', 'asc'])) {
                    $orderDir = $requestData['orderType'];
                }
                $rows = $instance->modelClass::siteOwner()->search($request)->orderBy($orderCol, $orderDir)->paginate($perPage);

                $pageData[$instance->crudName] = $rows;
            } else {
                $pageData[$instance->crudName] = $instance->modelClass::siteOwner()->latest()->paginate($perPage);
            }

            $view = $instance->getGroup() . '.' . $instance->crudName . '.index';
            $pageData['pgTitle'] = htmlLabel($instance->crudName . '_index');;

            return $this->parseViewCi($view, $pageData);

        };
        return $this->processResponse($showPageIndex);

    }


    public function create()
    {

        $showPageFn = function ($instance) {
            $instance->hasPermissionOrFail('create');

            $instance->beforeRenderCreatePage();
            $view = $instance->getGroup() . '.' . $this->crudName . '.create';
            $pageData['pgTitle'] = htmlLabel($instance->crudName . '_create');;
            return $this->parseViewCi($view, $pageData);
        };
        return $this->processResponse($showPageFn);
    }


    public function show($id)
    {

        $showPageFn = function (self $instance) use ($id) {
            $instance->hasPermissionOrFail('show');

            $instance->beforeRenderShowPage($id);
            $view = $instance->getGroup() . '.' . $this->crudName . '.show';
            $pageData = [];

            $pageData[$this->crudNameSingular] = $this->modelClass::siteOwner()->where($instance->getPkModelCol(), $id)->firstOrFail();
            $pageData['pgTitle'] = htmlLabel($instance->crudName . '_show');;

            return $this->parseViewCi($view, $pageData);
        };
        return $this->processResponse($showPageFn);
    }


    public function edit($id)
    {

        $editPage = function (self $instance) use ($id) {
            $instance->hasPermissionOrFail('edit');
            $instance->beforeRenderEditPage($id);
            $view = $instance->getGroup() . '.' . $this->crudName . '.edit';
            $pageData = [];
            $pageData[$this->crudNameSingular] = $this->modelClass::siteOwner()->where($instance->getPkModelCol(), $id)->firstOrFail();
            $pageData['pgTitle'] = htmlLabel($instance->crudName . '_edit');;
            return $this->parseViewCi($view, $pageData);
        };
        return $this->processResponse($editPage);

    }

    private function processResponse($callback)
    {
        try {
            return $callback($this);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', parseExceptionMessage($e));
        }
    }

    public function getModelBase()
    {
        return $this->modelClass;
    }


}