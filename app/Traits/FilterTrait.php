<?php

namespace App\Traits;

trait FilterTrait
{
    private function filterByActivity($query,$dbName,$active){
        if ($active['active'] ?? null)
            $query = $query->where("$dbName.is_active",1);
        return $query;
    }

    public function globalFilter($query,$fieldValue,$fieldName){
        if ( !is_null($fieldValue)  )
            $query = $query->where($fieldName,$fieldValue);
        return $query;
    }

    public function globalFilterWhereIn($query,$fieldValue,$fieldName){
        if ($fieldValue != null)
            $query = $query->whereIn($fieldName,$fieldValue);
        return $query;
    }

    public function globalFilterRelation($query, $fieldName,$fieldValue, $relation = 'students')
    {
        if ($fieldValue)
            $query = $query->whereHas($relation, function ($q) use ($fieldValue,$fieldName) {
                $q->where($fieldName, $fieldValue);
            });

        return $query;
    }

    public function globalFilterRelationWhereIn($query, $fieldName,$fieldValue, $relation = 'students')
    {
        if ($fieldValue)
            $query = $query->whereHas($relation, function ($q) use ($fieldValue,$fieldName) {
                $q->whereIn($fieldName, $fieldValue);
            });

        return $query;
    }

    public function globalHaving($query,$fieldName,$sign){
        if ($sign)
            return $query->having($fieldName, $sign, 0);
        return $query;
    }

    public function filterNotZero($query,$fieldValue,$fieldName){
        if ($fieldValue)
            $query = $query->where($fieldName,"!=",0);
        return $query;
    }

    public static function filterByDate($query, $start,$end="2080/01/01")
    {
        if ($start)
            return $query = $query->whereBetween('date', [$start, $end]);
        return $query;
    }

    public function filterDateRelation($query, $start, $end, $relation = 'accounting_document')
    {
        if ($start)
            $query = $query->whereHas($relation, function ($q) use ($start, $end) {
                $q->whereBetween('date', [$start, $end]);
            });

        return $query;
    }

    public function filterDetail($query, $detail, $account_id)
    {
        $account = AccountRepository::findStc($account_id);
        if ($account->detail != 0 && $detail != null) {
            $query->where('detail', $detail);
        }
        return $query;
    }

    public function filterByBarcode($query,$barcode)
    {
        if ($barcode){
            $query = $query->where('barcode', 'like', '%'.$barcode.'%')->limit(1);
        }

        return $query;
    }

    public function filterByNameOrCustomParameter($query, $searchStr, $filedName =null)
    {
        if ($searchStr)
            $query = $query->where('name', 'like', '%' . $searchStr . '%');
        if ($filedName)
            $query = $query->orWhere($filedName, 'like', '%' . $searchStr . '%');

        return $query;
    }

    public static function fieldSearchQuery($query,$item,$str){
        return $query->where($item['fieldName'], $item['type'], $str);
    }

    public static function arrayFieldSearchQuery($query,$item,$array){
        return $query->whereIn($item['fieldName'], $array);
    }

    public function paginateDecide($records, $paginateFlag=null,$isCollection=false)
    {
        if ($paginateFlag > 0)
            $items = $records->paginate($paginateFlag);
        else if (!$isCollection)
            $items = $records->get();
        else
            $items = $records;
        return $items;
    }

    public function simplePaginateDecide($collection, $paginateFlag)
    {
        if ($paginateFlag)
            $items = $collection->simplePaginate($paginateFlag);
        else
            $items = $collection->get();

        return $items;
    }
}
