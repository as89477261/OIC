<?php

class Food
{

    public function getById($id)
    {
        global $conn;
        $sql = "select * from tm_food where f_rowid_food = '$id'";
        $rs = $conn->Execute($sql);
        return $rs->FetchRow();
    }

    public function getAll()
    {
        global $conn;
        $sql = " SELECT * "
                . " FROM "
                . " tm_food"
                . " order by tm_food.F_FOODCODE asc";
        $rs = $conn->Execute($sql);
        return $rs->GetArray();
    }

    public function save($data)
    {
        if($data['f_rowid_food'] == 'NEW' || $data['f_rowid_food'] == ''){
            $data['f_rowid_food'] = uniqid('food');
            $isNew = true;
        }

        global $conn;
        $conn->Execute("alter session set nls_date_format='yyyy-mm-dd hh24:mi:ss'");
        $trans = new ADODB_Active_Record('TM_FOOD', array('F_ROWID_FOOD'));
        $trans->Load("F_ROWID_FOOD = '" . $data['f_rowid_food'] . "'");
        $trans->f_rowid_food = $data['f_rowid_food'];
        $trans->f_foodcode = $data['f_foodcode'];
        $trans->f_foodname = $data['f_foodname'];
        $trans->f_modifyuserid = $_SESSION['accID'];
        $trans->f_modifytime = date('Y-m-d His');
        if($isNew){
            $trans->f_createuserid = $_SESSION['accID'];
            $trans->f_createtime = date('Y-m-d His');
        }
        $trans->Save();
    }

    public function isUse($id)
    {
        global $conn;

        $sql = "select count(*) as c from TR_TRANFOOD where F_ROWID_FOOD = '$id'";
        $rs = $conn->Execute($sql);
        if($rs->fields['C'] > 0){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id)
    {
        global $conn;

        $sql = "delete from TM_FOOD where F_ROWID_FOOD = '$id'";
        $rs = $conn->Execute($sql);
    }
}