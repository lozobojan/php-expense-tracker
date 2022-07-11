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

    function uploadFile($allowed_extensions, $upload_dir, $client_filename, $tmp_name, $depth = 0){
        $new_filename = uniqid();
        $client_extension = end(explode('.', $client_filename));

        if(!in_array($client_extension, $allowed_extensions)){
            exit("Nedozovoljen format slike...");
        }
        $new_filename = $new_filename.".".$client_extension;

        $tmp_path = $tmp_name;
        $new_photo_path = $upload_dir.$new_filename;
        if(!copy($tmp_path, getBackDots($depth).$new_photo_path)){
            exit("Greska pri upload-u slike...");
        }

        return $new_photo_path;
    }

    function getBackDots($depth){
        $dots = "";
        while($depth > 0){
            $dots .= "../";
            $depth--;
        }
        return $dots;
    }

?>