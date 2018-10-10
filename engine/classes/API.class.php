<?php
/**
 * Created by PhpStorm.
 * User: Big_Energy
 * Date: 09.10.2018
 * Time: 23:49
 */
include_once 'MyPDO.class.php';

error_reporting(E_ALL);
ini_set('display_errors',1);

class API
{
    public $db;

    public function __construct()
    {
        $this->db = new MyPDO();
    }
    // удаление организации
    public function remove_org($id)
    {
        if (empty($id)) return false;

        try {
            $sql = "DELETE FROM organizations WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();// check affected rows using rowCount
            if ($count > 0) {
                $result_json = array('success' => 'Организация с ID ' . $id . ' удалена успешно.');
            } else {
                $result_json = array('error' => 'Ошибка выполнения запроса, организация с указанным ID не существует или что-то пошло не так!');
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        echo json_encode($result_json, JSON_UNESCAPED_UNICODE);
    }


}