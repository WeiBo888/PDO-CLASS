<?php
/**
 * Created by PhpStorm.
 * User: weibo
 * Date: 2018/6/7
 * Time: 下午3:15
 */

//类
class DB{
    //属性 -- 静态的描述
    /*
     * public
     * private
     * protected
     *
     * **/
    private $HOST;
    private $DB_NAME;
    private $DB_USER;
    private $DB_PASSWORD;
    private $pdo;//数据库连接对象
    //方法() -- 行为(function)

    //类的初始化方法:
    public function __construct($host, $db_name, $db_user, $db_password)
    {
        $this->HOST = $host;
        $this->DB_NAME = $db_name;
        $this->DB_USER = $db_user;
        $this->DB_PASSWORD = $db_password;
        $this->connect();//连接数据库
    }

    //创建PDO对象方法:
    private function connect(){
        try{
            $this->pdo = new PDO('mysql:host='.$this->HOST.';dbname='.$this->DB_NAME,$this->DB_USER,$this->DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        }catch (PDOException $e){
            die($e->getMessage());
        }
        
    }
    
    //关闭连接
    public function close()
    {
        $this->pdo = null;
    }
    
    //封装增删改查
    public function add($table, $data = [])
    {
        try{
            //处理keys
            $all_keys = array_keys($data);
            $columns = implode(',', $all_keys);

            //处理values
            $all_values = array_values($data);
            for ($i=0;$i<count($all_values);$i++){
                $all_values[$i] = $this->checkValue($all_values[$i]);
            }
            $values_string = implode(',',$all_values);
            $sql = "INSERT INTO $table ($columns) VALUES ($values_string)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    public function delete($table, $condition = [])
    {
        try {
            $con = $this->checkWhere($condition);
            $sql = "DELETE FROM $table WHERE $con";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            echo 'DELETE SUCCESSFULLY';
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    public function update($table, $data = [], $condition = [])
    {
        try{
            $con = $this->checkWhere($condition);
            $all_keys = array_keys($data);

            $all_values = array_values($data);
            for ($i=0;$i<count($all_values);$i++){
                $all_values[$i] = $this->checkValue($all_values[$i]);
                $item[$i] = $all_keys[$i] . '=' . $all_values[$i];
            }
            $item_string = implode(',',$item);
            $sql = "UPDATE $table  SET $item_string WHERE $con";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            echo 'Update successfully';
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    public function search($item = '*',$table,$condition)
    {
        try{
            $con = $this->checkWhere($condition);

            if (!is_array($item)){
                $value_string = '*';
            }else {
                $value_string = implode(',', $values);
            }
            $sql = "SELECT $value_string FROM $table WHERE $con";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            print_r($result);
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    //处理value参数
    private function checkValue($value)
    {
        $temp = trim($value);
        if (is_string($value)){
            return "'$temp'";
        }
        return $temp;
    }

    //检查where的条件
    /*
     * [ 'name' => ['=','Tom'],
     *   'age' => ['>',18]
     * ]
     * **/
    public function checkWhere($condition){
        $con_str = '';
        if (!empty($condition)) {
            if (is_array($condition)) {
                foreach ($condition as $key => $value){
                    if (is_array($value)){
                        $con_str .= "$key $value[0] " . $this->checkValue($value[1]) . ' AND ';
                    }
                }
                $con_str = substr($con_str,0,-5);
                return trim($con_str);

            }else{
                return trim($condition);
            }
        }else{
            return "1=1";
        }

    }

}


