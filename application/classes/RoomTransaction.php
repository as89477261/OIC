<?php

class RoomTransaction
{

	public function getTel($id)
	{
        global $conn;
        $sql = "select a.F_TEL"
                . " FROM tbl_account a"
                . " where "
                . " TO_CHAR(a.f_acc_id) = '$id'";
        //echo $sql;
        $rs = $conn->Execute($sql);
        return $rs->fields['F_TEL'];
	}

    public function getTransactionById($id)
    {
        global $conn;
        $sql = "select a.*, b.f_name||' '||b.F_LAST_NAME as F_CREATE_NAME, c.F_ROOMNAME"
                . " from tr_transaction a, tbl_account b, tm_room c"
                . " where a.F_ROWID = '" . $id . "'"
                . " and a.F_ROWID_ROOM = c.F_ROWID_ROOM"
                . " and a.f_userid = TO_CHAR(b.f_acc_id)";
        //echo $sql;
        $rs = $conn->Execute($sql);
        return $rs->FetchRow();
    }

    public function getTransactionByDate($date)
    {
        global $conn;
        $sql = "SELECT a.*, b.*, to_char(a.F_CREATETIME, 'HH24:MI') as F_CREATE_TIME
                FROM tr_transaction a, tbl_account b
                where a.F_DATE_BOOK = TO_DATE('$date', 'YYYY-MM-DD')
                    and a.F_CANCEL = 0
                    and a.f_userid = TO_CHAR(b.f_acc_id)
                ORDER BY a.f_rowid_room";
        //echo $sql;
        $rs = $conn->Execute($sql);
        $d = array();
        while ($data = $rs->FetchRow()) {
            $d[$data['F_ROWID_ROOM']][] = $data;
        }
        return $d;
    }

    public static function getBookingTitle($transaction)
    {
        $title = '';
        $title .= '' . $transaction['F_ROOMNAME'] . ' : ';
        $title .= '<b>' . substr($transaction['F_TIMESTART'], 0, 2) . ':' . substr($transaction['F_TIMESTART'], 2, 2);
        $title .= ' - ' . substr($transaction['F_TIMEEND'], 0, 2) . ':' . substr($transaction['F_TIMEEND'], 2, 2) . '</b>';
        $title .= '<br />����ͧ��Ъ�� : <b>' . $transaction['F_SUBJECT'] . '</b>';
        $title .= '<br />��иҹ : ' . $transaction['F_CHAIRMAN'];
        $title .= '<br />������������Ъ������ : ' . $transaction['F_QUANTITY_IN'];
        $title .= '<br />������������Ъ����¹͡ : ' . $transaction['F_QUANTITY_OUT'];
        list($y, $m, $d) = explode('-', $transaction['F_CREATETIME']);
        $title .= '<br />�ѹ���ͧ : ' . $d . '/' . $m . '/' . ($y + 543) . ' ' . $transaction['F_CREATE_TIME'];
        $title .= '<br />���ͧ : ' . $transaction['F_NAME'] . ' ' . $transaction['F_LAST_NAME'];
        $title .= '<br />�����˵� : ' . $transaction['F_REMARK'];

        return $title;
    }

    public static function getAllTransaction($date_from, $date_to, $room_id, $user_id)
    {
        global $conn;
        $sql = "select * from tr_transaction a, tm_room b, tbl_account c"
                . " where "
                . " b.F_ROWID_ROOM = a.F_ROWID_ROOM"
                . " and a.F_CANCEL = 0"
                . " and a.F_CREATEUSERID = TO_CHAR(c.f_acc_id)"
                . " and a.F_DATE_BOOK >= TO_DATE('$date_from', 'YYYY-MM-DD')"
                . " and a.F_DATE_BOOK <= TO_DATE('$date_to', 'YYYY-MM-DD')";
        if (trim($room_id) != '') {
            $sql .= " and a.F_ROWID_ROOM = '" . trim($room_id) . "'";
        }
        if (trim($user_id) != '') {
            $sql .= " and (a.F_CREATEUSERID = '" . trim($user_id) . "' or a.f_userid = '" . trim($user_id) . "')";
        }
        $sql .= " order by a.F_DATE_BOOK asc, a.F_TIMESTART asc, b.F_ROWID_SUBCATAGORIES desc, b.F_ROOMCODE asc";
        //echo $sql;
        $rs = $conn->Execute($sql);
        return $rs->GetArray();
    }

    public function getAccount()
    {
        global $conn;
        $sql = "select * from tbl_account order by f_name asc";
        $rs = $conn->Execute($sql);
        return $rs->GetArray();
    }

    public function getFoodTransaction($trans_id)
    {
        global $conn;
        $sql = "select * from tr_tranfood a, tm_food b where a.F_ROWID_FOOD = b.F_ROWID_FOOD and a.F_TRANSACTION_ROWID = '$trans_id'";
        $rs = $conn->Execute($sql);
        return $rs->GetArray();
    }

    public function getAccountRole($id)
    {
        global $conn;
        //$conn->debug = true;
//        $sql = "
//            SELECT a.*,
//                r.F_ROLE_NAME as role_name,
//                o.f_org_name as org_name,
//                po.f_org_name as p_org_name
//            FROM tbl_passport p,
//                tbl_account a,
//                tbl_role r,
//                tbl_organize o,
//                tbl_organize po
//            WHERE p.f_default_role = 1
//             AND p.f_acc_id = a.f_acc_id
//             AND p.f_role_id = r.f_role_id
//             AND r.f_org_id = o.f_org_id
//             AND o.f_org_pid = po.f_org_id
//             AND p.f_acc_id = '$id'
//        ";
        $sql = "select * from tbl_account where f_acc_id = '$id'";
        $rs = $conn->Execute($sql);
        $data = $rs->FetchRow();

        $sql = "select * from oic54.appv_personnel where a_email like '" . $data['F_LOGIN_NAME'] . "@%'";
        //echo $sql;
        $rs = $conn->Execute($sql);
        $data = $rs->FetchRow();

        //echo $data['A_SECTION_CODE'].'<br>';
        $sql = "
            select DESCRIPTION
            from oic54.appm_section
            where SECTION_CODE = (
                select max(PARENT_SECTION)
                from oic54.appm_section
                where section_code = '" . $data['A_SECTION_CODE'] . "'
            )";
        //echo $sql;
        $rs = $conn->Execute($sql);

        $data['SECTION_PARENT'] = $rs->fields['DESCRIPTION'];
        //$data['F_TEL'] = $data
        return $data;
    }

    public function cancelTransaction($id)
    {
        global $conn;
        //$conn->debug = true;
        $conn->Execute("alter session set nls_date_format='yyyy-mm-dd hh24:mi:ss'");

        $trans = new ADODB_Active_Record('TR_TRANSACTION', array('F_TRANSACTION_ROWID'));
        $trans->Load("F_TRANSACTION_ROWID = '" . $id . "'");
        $trans->f_rowid = $id;
        $trans->f_cancel = '1';
        $trans->f_canceluserid = $_SESSION['accID'];
        $trans->f_canceltime = date('Y-m-d His');
        $trans->Save();

        //exit();
    }

    public function validate($room, $date, $start, $end, $trans_id = '')
    {
        global $conn;

        $sql = "
            select *
            from TR_TRANSACTION
            where
                f_rowid_room = '$room'
                and f_date_book = TO_DATE('$date', 'YYYY-MM-DD')
                and SUBSTR(f_timestart,0,4) < '" . $end . "'
                and SUBSTR(f_timeend,0,4) > '" . $start . "'
                and f_cancel = 0
        ";
        if($trans_id != ''){
            $sql.=" and F_TRANSACTION_ROWID != '$trans_id'";
        }
        //echo $sql;
        $rs = $conn->Execute($sql);
        if ($rs->numrows() == 0) {
            return true;
        } else {
            return false;
        }
    }

	public function getStatistic($date_from, $date_to)
	{
        global $conn;
		$sql = "
			select f_rowid_room, count(*) as sum_count, sum(F_QUANTITY_IN)+sum(F_QUANTITY_OUT) as sum_quantity
			from tr_transaction a 
			where f_date_book >= TO_DATE('$date_from', 'YYYY-MM-DD')
			and f_date_book <= TO_DATE('$date_to', 'YYYY-MM-DD')
			group by f_rowid_room
		";
        $rs = $conn->Execute($sql);
		$rec = array();
		while($data = $rs->FetchRow()){
			$rec[$data['F_ROWID_ROOM']] = $data;
		}

		return $rec;		
	}

}
