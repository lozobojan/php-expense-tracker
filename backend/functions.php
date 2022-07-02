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

    function generateSelectQuery($table, $columns = ['*'], $conditions = []){
        $columnsToSelect = implode(',', $columns);
        $sql = "SELECT $columnsToSelect FROM $table WHERE 1=1 ";
        foreach($conditions as $key => $value){
            $sql .= " AND  $key = '$value' ";
        }
        return $sql;
    }

    function authorize(){
        if(!isset($_SESSION['login']) || $_SESSION['login'] !== true){
            header('location:login.php');
        }
    } 

?>