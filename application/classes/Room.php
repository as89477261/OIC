<?php

class Room {

    public function getAllRoom()
    {
        global $conn;
        $sql = " SELECT * "
                . " FROM "
                . " tm_room  INNER JOIN tm_subcatagories"
                . " ON  tm_room.f_rowid_subcatagories = tm_subcatagories.f_rowid_subcatagories"
                . " INNER JOIN  tm_catagories"
                . " ON  tm_subcatagories.f_rowid_catagories = tm_catagories.f_rowid_catagories"
                . " order by tm_room.F_ROWID_SUBCATAGORIES desc, tm_room.F_ROOMCODE asc, tm_room.f_roomname asc";
        $rs = $conn->Execute($sql);
        return $rs->GetArray();
    }

    public function getById($id)
    {
        global $conn;
        $sql = " SELECT * "
                . " FROM "
                . " tm_room  INNER JOIN tm_subcatagories"
                . " ON  tm_room.f_rowid_subcatagories = tm_subcatagories.f_rowid_subcatagories"
                . " INNER JOIN  tm_catagories"
                . " ON  tm_subcatagories.f_rowid_catagories = tm_catagories.f_rowid_catagories"
                . " Where tm_room.F_ROWID_ROOM = '$id' "
                . " order by tm_room.f_rowid_subcatagories desc, tm_room.f_roomname asc";
        $rs = $conn->Execute($sql);
        return $rs->FetchRow();
    }

    public static function getTransFood($trans_id)
    {
        global $conn;
        $sql = "select * from tr_tranfood a, tm_food b
                where a.f_rowid_food = b.f_rowid_food
                      and a.f_transaction_rowid = '$trans_id'";
        $rs = $conn->Execute($sql);
        return $rs->GetArray();
    }

    public static function dateFormat($ymd)
    {
        $month = array(
            '01' => '�.�.',
            '02' => '�.�.',
            '03' => '��.�.',
            '04' => '��.�.',
            '05' => '�.�.',
            '06' => '��.�.',
            '07' => '�.�.',
            '08' => '�.�.',
            '09' => '�.�.',
            '10' => '�.�.',
            '11' => '�.�.',
            '12' => '�.�.'
        );
        list($y, $m, $d) = explode('-', $ymd);
        return $d . ' ' . $month[$m] . ' ' . substr($y + 543, 2, 2);
    }

    public static function dateFormat2($ymd)
    {
        list($y, $m, $d) = explode('-', $ymd);
        return $d . '/' . $m . '/' . $y;
    }

	public function save($data)
	{
        if($data['f_rowid_room'] == '' || $data['f_rowid_room'] == 'NEW'){
            $data['f_rowid_room'] = uniqid('room');
            $isNew = true;
        }

        global $conn;
        $conn->Execute("alter session set nls_date_format='yyyy-mm-dd hh24:mi:ss'");

        $trans = new ADODB_Active_Record('TM_ROOM', array('F_ROWID_ROOM'));
        $trans->Load("F_ROWID_ROOM = '" . $data['f_rowid_room'] . "'");
        $trans->f_rowid_room = $data['f_rowid_room'];
        $trans->f_roomcode = $data['f_roomcode'];
        $trans->f_roomname = $data['f_roomname'];
        $trans->f_roomcapacity = $data['f_roomcapacity'];
        $trans->f_roomunit = $data['f_roomunit'];
        $trans->f_roombuilding = $data['f_roombuilding'];
        $trans->f_roomfloor = $data['f_roomfloor'];
        $trans->f_roomdescription = $data['f_roomdescription'];
        $trans->f_roomstatus = $data['f_roomstatus'];
        $trans->f_modifyuserid = $_SESSION['accID'];
        $trans->f_modifytime = date('Y-m-d His');
        $trans->f_rowid_subcatagories = $data['f_rowid_subcatagories'];
        if($isNew){
            $trans->f_createuserid = $_SESSION['accID'];
            $trans->f_createtime = date('Y-m-d His');
        }
        $trans->Save();

	}

    public function isUse($id)
    {
        global $conn;

        $sql = "select count(*) as c from TR_TRANSACTION where F_ROWID_ROOM = '$id'";
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

        $sql = "delete from tm_room where f_rowid_room = '$id'";
        $rs = $conn->Execute($sql);
    }

    public function getCatagory()
    {
        global $conn;

        $sql = "select * from TM_SUBCATAGORIES order by F_ROWID_SUBCATAGORIES desc";
        $rs = $conn->Execute($sql);
        return $rs->GetArray();
    }
	
	
	public function saveCategory($data)
	{
        if($data['f_rowid_subcatagories'] == '' || $data['f_rowid_subcatagories'] == 'NEW'){
            $data['f_rowid_subcatagories'] = uniqid('sub');
            $isNew = true;
        }

        global $conn;
        $conn->Execute("alter session set nls_date_format='yyyy-mm-dd hh24:mi:ss'");

        $trans = new ADODB_Active_Record('TM_SUBCATAGORIES', array('f_rowid_catagories'));
        $trans->Load("f_rowid_subcatagories = '" . $data['f_rowid_subcatagories'] . "'");
        $trans->f_rowid_subcatagories = $data['f_rowid_subcatagories'];
        $trans->f_subcatagories_code = $data['f_subcatagories_code'];
        $trans->f_rowid_catagories = $data['f_rowid_catagories'];
        $trans->f_subcatagories_name = $data['f_subcatagories_name'];
        $trans->f_subcatagories_status = $data['f_subcatagories_status'];
        $trans->f_catagories_status = 1;
        $trans->f_modifyuserid = $_SESSION['accID'];
        $trans->f_modifytime = date('Y-m-d His');
        if($isNew){
            $trans->f_createuserid = $_SESSION['accID'];
            $trans->f_createtime = date('Y-m-d His');
        }
        $trans->Save();
	}
	
	public function getCategoryById($id)
	{
        global $conn;
        $sql = " SELECT * FROM tm_subcatagories Where f_rowid_subcatagories = '$id'";
        $rs = $conn->Execute($sql);
        return $rs->FetchRow();
	}
	
	public function isUseCategory($id)
	{
        global $conn;

        $sql = "select count(*) as c from TM_ROOM where F_ROWID_SUBCATAGORIES = '$id'";
        $rs = $conn->Execute($sql);
        if($rs->fields['C'] > 0){
            return true;
        }else{
            return false;
        }
	}
	
	public function deleteCategory($id)
	{
        global $conn;

        $sql = "delete from tm_subcatagories where f_rowid_subcatagories = '$id'";
        $rs = $conn->Execute($sql);
	}
}
