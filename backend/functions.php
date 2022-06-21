<?php 

    function readInput($array, $key, $validators = []){
        if(isset($array[$key]) && $array[$key] != ""){
            return $array[$key];
        }
        return false;
    }

    function generateInsertQuery($table, $columns){
        $sql = "INSERT INTO $table ";
        $columnsTemp = [];
        $valuesTemp = [];
        foreach($columns as $key => $value){
            $columnsTemp[] = $key;
            $valuesTemp[] = is_numeric($value) ? $value : "'$value'";
        }
        $sql .= "(".implode(",", $columnsTemp).") VALUES (".implode(',', $valuesTemp).")";

        return $sql;
    }
?>