<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait ModelSearchTrait
{

    public function scopeSearch($query, Request $request)
    {

        $keyword = $request->get('search');

        $requestData = $request->query();
        $fields = $this->fillable;
        $enumFields = self::getEnumFields();

        $query = $query->where(function ($q) use ($fields, $requestData, $enumFields) {
            foreach ($fields as $field) {
                if (!isset($enumFields[$field]) && isset($requestData[$field]) && !empty($requestData[$field])) {
                    $q = $q->orWhere($field, 'LIKE', "%" . $requestData[$field] . "%");
                } else if (!empty($requestData['search'])) {
                    $q = $q->orWhere($field, 'LIKE', "%" . $requestData['search'] . "%");
                }
            }
        });


        foreach ($fields as $field) {

            if (isset($enumFields[$field]) && isset($requestData[$field])) {
                $fieldVal = (string)$requestData[$field];
                $query = $query->where($field, $fieldVal);
            }
        }
        if (in_array('created_at',$fields) && !empty($requestData['from_date']) && !empty($requestData['to_date'])) {
            try {
                $fromDate = Carbon::parse($requestData['from_date']);
                $toDate = Carbon::parse($requestData['to_date']);
                $query = $query->where('created_at', [$fromDate, $toDate]);
            } catch (\Exception $e) {
            }

        }
        return $query;

    }

}