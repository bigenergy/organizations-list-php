<?php
/**
 * Created by PhpStorm.
 * User: Big_Energy
 * Date: 09.10.2018
 * Time: 23:49
 */
include_once 'MyPDO.class.php';

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
            $count = $stmt->rowCount();
            if ($count > 0) {
                $result_json = array('good' => 'Организация с ID ' . $id . ' удалена успешно.');
            } else {
                $result_json = array('error' => 'Ошибка выполнения запроса, организация с указанным ID не существует или что-то пошло не так!');
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }

        return json_encode($result_json, JSON_UNESCAPED_UNICODE);
    }
    // функция проверки на сущестование такого КПП или ИНН в базе

    public function check_org($orgtype, $INN, $KPP)
    {
        if ($orgtype = 'IP') {
            if (empty($orgtype) || empty($INN)) return false;
            $checksql = "SELECT * FROM organizations WHERE inn = '$INN' ";
        } elseif ($orgtype = 'UL') {
            if (empty($orgtype) || empty($INN) || empty($KPP)) return false;
            $checksql = "SELECT * FROM organizations WHERE kpp = '$KPP' ";
        }

        $stmt = $this->db->prepare($checksql);
        try {
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count > 0) {
                return true;
            } else {
                return false;
            }
        }
        catch(PDOException $e){
            return $e->getMessage();
        }

    }
    // добавление организации

    /**
     * @param $orgname
     * @param $orgtype
     * @param $INN
     * @param $KPP
     * @param $phone
     * @param $email
     * @return bool|false|string
     */
    public function add_org($orgname, $orgtype, $INN, $KPP, $phone, $email)
    {
        if ($orgtype == 'IP') {
            if (empty($orgname) || empty($orgtype) || empty($INN) || empty($phone)) return false;
        } elseif ($orgtype == 'UL') {
            if (empty($orgname) || empty($orgtype) || empty($INN) || empty($KPP) || empty($phone)) return false;
        }

        $ru = preg_match('~[а-яё]+~iu',$orgname);
        $en = preg_match('~[a-z]+~i', $orgname);

        if (! ($ru ^ $en) ) {
            $result_json = array('errorlang' => 'Наименование организации должно использовать или кириллицу, или латиницу!');
            echo json_encode($result_json, JSON_UNESCAPED_UNICODE);
            return false;
        }

        if (strlen($orgname) > 255) {
            $result_json = array('error' => 'Наименование организации превышает установленный лимит в 255 символов!');
            echo json_encode($result_json, JSON_UNESCAPED_UNICODE);
            return false;
        }
        if ($email != NULL) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // invalid emailaddress
                $result_json = array('error' => 'Неверный E-Mail адрес!');
                echo json_encode($result_json, JSON_UNESCAPED_UNICODE);
                return false;
            }
        }
        if($orgtype == 'IP') {
            if(strlen($INN) !== 12) {
                $result_json = array('error' => 'ИНН должен составлять 12 символов!');
                echo json_encode($result_json, JSON_UNESCAPED_UNICODE);
                return false;
            }
        } elseif($orgtype == 'UL') {
            if(strlen($INN) !== 10 && strlen($KPP) !== 9) {
                $result_json = array('error' => 'ИНН должен составлять 10 символов, а КПП должен составлять 9!');
                echo json_encode($result_json, JSON_UNESCAPED_UNICODE);
                return false;
            }
        }

        try {
            $sql = "INSERT INTO `organizations` (`id`, `name`, `type`, `inn`, `kpp`, `phone`, `email`) VALUES (NULL, :orgname, :orgtype, :INN, :KPP, :phone, :email);";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':orgname', $orgname,  PDO::PARAM_STR);
            $stmt->bindParam(':orgtype', $orgtype,  PDO::PARAM_STR);
            $stmt->bindParam(':INN', $INN,  PDO::PARAM_STR);
            $stmt->bindParam(':KPP', $KPP,  PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone,  PDO::PARAM_STR);
            $stmt->bindParam(':email', $email,  PDO::PARAM_STR);

            $check_already = API::check_org($orgtype, $INN, $KPP);
            if (!$check_already) {
                $stmt->execute();
                $result_json = array('success' => 'Организация с названием ' . $orgname . ' успешно добавлена!');
                return json_encode($result_json, JSON_UNESCAPED_UNICODE);
            } else {
                $result_json = array('already' => 'Организация с таким ИНН или КПП уже зарегистрирована в базе');
                return json_encode($result_json, JSON_UNESCAPED_UNICODE);
            }
        } catch(PDOException $e) {
            return $e->getMessage();
        }

    }

    // функция редактирования организации

    public function edit_org($orgID, $orgname, $orgtype, $INN, $KPP, $phone, $email)
    {
        if ($orgtype == 'IP') {
            if (empty($orgID) || empty($orgname) || empty($orgtype) || empty($INN) || empty($phone)) return false;
        } elseif ($orgtype == 'UL') {
            if (empty($orgID) || empty($orgname) || empty($orgtype) || empty($INN) || empty($KPP) || empty($phone)) return false;
        }

        $ru = preg_match('~[а-яё]+~iu',$orgname);
        $en = preg_match('~[a-z]+~i', $orgname);

        if (! ($ru ^ $en) ) {
            $result_json = array('errorlang' => 'Наименование организации должно использовать или кириллицу, или латиницу!');
            echo json_encode($result_json, JSON_UNESCAPED_UNICODE);
            return false;
        }

        if (strlen($orgname) > 255) {
            $result_json = array('error' => 'Наименование организации превышает установленный лимит в 255 символов!');
            echo json_encode($result_json, JSON_UNESCAPED_UNICODE);
            return false;
        }
        if ($email != NULL) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // invalid emailaddress
                $result_json = array('error' => 'Неверный E-Mail адрес!');
                echo json_encode($result_json, JSON_UNESCAPED_UNICODE);
                return false;
            }
        }
        if($orgtype == 'IP') {
            if(strlen($INN) !== 12) {
                $result_json = array('error' => 'ИНН должен составлять 12 символов!');
                echo json_encode($result_json, JSON_UNESCAPED_UNICODE);
                return false;
            }
        } elseif($orgtype == 'UL') {
            if(strlen($INN) !== 10 && strlen($KPP) !== 9) {
                $result_json = array('error' => 'ИНН должен составлять 10 символов, а КПП должен составлять 9!');
                echo json_encode($result_json, JSON_UNESCAPED_UNICODE);
                return false;
            }
        }

        try {
            $sql = "UPDATE `organizations` SET `name`=:orgname,`type`=:orgtype,`inn`=:INN,`kpp`=:KPP,`phone`=:phone,`email`=:email WHERE `id` = '$orgID'";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':orgname', $orgname,  PDO::PARAM_STR);
            $stmt->bindParam(':orgtype', $orgtype,  PDO::PARAM_STR);
            $stmt->bindParam(':INN', $INN,  PDO::PARAM_STR);
            $stmt->bindParam(':KPP', $KPP,  PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone,  PDO::PARAM_STR);
            $stmt->bindParam(':email', $email,  PDO::PARAM_STR);

            $check_already = API::check_org($orgtype, $INN, $KPP);
            if (!$check_already) {
                $stmt->execute();
                $result_json = array('success' => 'Информация об организации с названием ' . $orgname . ' успешно обновлена!');
                return json_encode($result_json, JSON_UNESCAPED_UNICODE);
            } else {
                $result_json = array('already' => 'Организация с таким ИНН или КПП уже зарегистрирована в базе');
                return json_encode($result_json, JSON_UNESCAPED_UNICODE);
            }
        } catch(PDOException $e) {
            return $e->getMessage();
        }

    }

    // функция вывода списка всех организаций или по определенному ID
    public function list_org($id=null) {

        if($id != NULL) {
            $array = $this->db->run("SELECT * FROM organizations WHERE id = '$id'")->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $array = $this->db->run("SELECT * FROM organizations")->fetchAll(PDO::FETCH_ASSOC);
        }


        return json_encode($array,JSON_UNESCAPED_UNICODE);

    }


}
