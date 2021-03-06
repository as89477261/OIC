<?php

class RoomController extends ECMController {

    public function init()
    {
        global $config;
        global $policy;

        if ($_SESSION['accID'] == '') {
            echo '
            <table style="height:100%" width="100%"   cellpadding="0" cellspacing="1">
                <tr>
                    <td width="75%" align="left" valign="top">
                        <div id="worksapceDIV"><center><img src="https://backoffice.oic.or.th/workflow/images/themes/SLC/logo_main.jpg" ></center></div>
                    </td>
                </tr>
            </table>';
            exit();
        }

        if (!$policy->canRoomAccess()) {
            echo '��ҹ������Ѻ�Է���㹡���������к��ͧ��ͧ��Ъ��';
            exit();
        }

        Zend_Loader::loadClass('Zend_View');

        // Inherit Custom Zend_View to protected member ECMView
        $this->ECMView = new Zend_View ( );

        // Setup view script path
        $this->ECMView->setScriptPath("{$config ['appPath']}application/views/room/");

        // Initialize Data
        $this->data = Array();
        //$this->setECMViewModule('room');
    }

    public function indexAction()
    {
        $date = $_GET['date'];
        if ($date == '') {
            $date = date('d/m/Y');
            $sel_date = date('Y-m-d');
        } else {
            $l = explode('/', $date);
            $sel_date = ($l[2]) . '-' . $l[1] . '-' . $l[0];
        }

        $room = new Room();
        $rooms = $room->getAllRoom();

        $roomTransaction = new RoomTransaction();
        $transactions = $roomTransaction->getTransactionByDate($sel_date);

        //echo 'room@index';
        $this->ECMView->assign('date', $date);
        $this->ECMView->assign('rooms', $rooms);
        $this->ECMView->assign('transactions', $transactions);
        $output = $this->ECMView->render('index.phtml');
        echo $output;
    }

    public function bookingAction()
    {
        $room = new Room();
        $rooms = $room->getAllRoom();
        $this->ECMView->assign('rooms', $rooms);

        $food = new Food();
        $foods = $food->getAll();
        $this->ECMView->assign('foods', $foods);

        $roomTransaction = new RoomTransaction();
        $accounts = $roomTransaction->getAccount();
        $this->ECMView->assign('accounts', $accounts);

        $trans_id = $_GET['id'];
        if ($trans_id == '') {
            $this->ECMView->assign('mode', 'add');
            if ($_GET['date']) {
                $trans['F_DATE_BOOK'] = $_GET['date'];
            } else {
                $trans['F_DATE_BOOK'] = date('Y-m-d');
            }
            $trans['F_USERID'] = $_SESSION['accID'];
            $trans['F_TIMEEND'] = '160000';


			$roomTransaction = new RoomTransaction();
			$tel = $roomTransaction->getTel($_SESSION['accID']);
            $trans['F_TEL'] = $tel;
        } else {
            $this->ECMView->assign('mode', 'edit');
            $trans = $roomTransaction->getTransactionById($trans_id);

            $foodTransaction = $roomTransaction->getFoodTransaction($trans_id);
            foreach ($foodTransaction as $foodTrans) {
                $data[$foodTrans['F_ROWID_FOOD']] = $foodTrans;
            }
            $this->ECMView->assign('foodTransaction', $data);
        }
        $this->ECMView->assign('trans', $trans);

        $output = $this->ECMView->render('roomBooking.phtml');
        echo $output;
    }

    public function savebookingAction()
    {
        global $conn;
        //$conn->debug = true;
        //var_dump($_POST);
        //exit();
        $conn->Execute("alter session set nls_date_format='yyyy-mm-dd hh24:mi:ss'");

        if ($_POST['F_TRANSACTION_ROWID'] == '') {
            $id = uniqid('trans');
        } else {
            $id = $_POST['F_TRANSACTION_ROWID'];
        }

        $trans = new ADODB_Active_Record('TR_TRANSACTION', array('F_TRANSACTION_ROWID'));
        $trans->Load("F_TRANSACTION_ROWID = '" . $id . "'");
        $trans->f_rowid = $id;
        $trans->f_transaction_rowid = $id;
        $trans->f_rowid_room = $_POST['room'];
        $trans->f_chk_transaction_rowid = '';
        $trans->f_subject = $_POST['subject'];
        $trans->f_chairman = $_POST['chairman'];
        $trans->f_quantity_in = $_POST['quantity_in'];
        $trans->f_quantity_out = $_POST['quantity_out'];
        $trans->f_quantityname = '';
        $trans->f_product = '';
        $trans->f_activity = '';
        list($d, $m, $y) = explode('/', $_POST['book_date']);
        $trans->f_date_book = $y . '-' . $m . '-' . $d;
        $trans->f_timestart = $_POST['start_h'] . $_POST['start_m'] . '00';
        $trans->f_timeend = $_POST['end_h'] . $_POST['end_m'] . '00';
        $trans->f_userid = $_POST['user_id'];
        $trans->f_remark = $_POST['remark'];

        if ($_POST['F_TRANSACTION_ROWID'] == '') {
            $trans->f_createuserid = $_SESSION['accID'];
            $trans->f_createtime = date('Y-m-d') . ' ' . date('His');
        }
        $trans->f_modifyuserid = $_SESSION['accID'];
        $trans->f_modifytime = date('Y-m-d') . ' ' . date('His');

        $trans->f_confirm = '1';
        $trans->f_confirmuserid = $_POST['user_id'];
        $trans->f_confirmtime = date('Y-m-d') . ' ' . date('His');
        $trans->f_cancel = '0';
        $trans->f_canceluserid = '';
        $trans->f_canceltime = '';
        $trans->f_cause = '';
        $trans->f_status = '1';
        $trans->f_roomuseprice = '';
        $trans->f_activity_code = '';
        $trans->f_project_code = '';
        $trans->f_in_ex_use = '';
		$trans->f_tel_no = $_POST['f_tel_no'];

        $roomTransaction = new RoomTransaction();
        $account = $roomTransaction->getAccount();
        $trans->f_tel = $account['f_tel'];
        $trans->Save();

        $food = array();
        foreach ($_POST as $key => $val) {
            if (strpos($key, 'f_checkfood_food') !== false) {
                $food[] = $val;
            }
        }
//        var_dump($food);
        $sql = "delete from TR_TRANFOOD where f_transaction_rowid = '" . $id . "'";
        $conn->Execute($sql);
        foreach ($food as $food_id) {
//            $sql = "select F_ROWID from TR_TRANFOOD where F_TRANSACTION_ROWID = '" . $id . "' and F_ROWID_FOOD = '" . $food_id . "'";
//            $rs = $conn->Execute($sql);
//            $food_row_id = $rs->fields['F_ROWID'];
//            if ($food_row_id == '') {
            $food_row_id = uniqid('trfood');
//                $food_new = true;
//            } else {
//                $food_new = false;
//            }

            if ((int) $_POST['f_tranfoodquantity_m_' . $food_id] + (int) $_POST['f_tranfoodquantity_a_' . $food_id] > 0) {
                $trans = new ADODB_Active_Record('TR_TRANFOOD', array('F_ROWID'));
                $trans->Load("F_ROWID = '" . $food_row_id . "'");
                $trans->f_rowid = $food_row_id;
                $trans->f_transaction_rowid = $id;
                $trans->f_rowid_food = $food_id;
                $trans->f_tranfoodquantity = $_POST['f_tranfoodquantity_m_' . $food_id];
                $trans->f_tranfoodprice = $_POST['f_tranfoodquantity_a_' . $food_id];
                $trans->f_tranfoodopen = '';
                if ($food_new === true) {
                    $trans->f_createuserid = $_SESSION['accID'];
                    $trans->f_createtime = date('Y-m-d') . ' ' . date('His');
                }
                $trans->f_modifyuserid = $_SESSION['accID'];
                $trans->f_modifytime = date('Y-m-d') . ' ' . date('His');
                $trans->Save();
            }
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function bookingListAction()
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        ini_set('display_errors', 'on');


        if ($_GET['date_from'] != '') {
            $date_from = $_GET['date_from'];
            $date_to = $_GET['date_to'];
            $l = explode('/', $date_from);
            $sel_date_from = $l[2] . '-' . $l[1] . '-' . $l[0];
            $l = explode('/', $date_to);
            $sel_date_to = $l[2] . '-' . $l[1] . '-' . $l[0];
        } else {
            $date_from = date('01/m/Y');
            $date_to = date('t/m/Y');
            $sel_date_from = date('Y-m-01');
            $sel_date_to = date('Y-m-t');
        }
        $user_id = $_SESSION['accID'];
        if (trim($user_id) == '') {
            $user_id = '-';
        }

        $roomTransaction = new RoomTransaction();
        $transactions = $roomTransaction->getAllTransaction($sel_date_from, $sel_date_to, $_GET['room'], $user_id);
        //var_dump($transactions);

        $room = new Room();
        $rooms = $room->getAllRoom();
        $this->ECMView->assign('room', $_GET['room']);
        $this->ECMView->assign('rooms', $rooms);

        $this->ECMView->assign('date_from', $date_from);
        $this->ECMView->assign('date_to', $date_to);
        $this->ECMView->assign('transactions', $transactions);
        $this->ECMView->assign('mode', 'self');
        echo $this->ECMView->render('bookingList.phtml');
    }

    public function bookingAllAction()
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        ini_set('display_errors', 'on');

        if ($_GET['date_from'] != '') {
            $date_from = $_GET['date_from'];
            $date_to = $_GET['date_to'];
            $l = explode('/', $date_from);
            $sel_date_from = $l[2] . '-' . $l[1] . '-' . $l[0];
            $l = explode('/', $date_to);
            $sel_date_to = $l[2] . '-' . $l[1] . '-' . $l[0];
        } else {
            $date_from = date('d/m/Y');
            $date_to = date('d/m/Y');
            $sel_date_from = date('Y-m-d');
            $sel_date_to = date('Y-m-d');
        }

        $roomTransaction = new RoomTransaction();
        $transactions = $roomTransaction->getAllTransaction($sel_date_from, $sel_date_to, $_GET['room']);
        //var_dump($transactions);

        $room = new Room();
        $rooms = $room->getAllRoom();
        $this->ECMView->assign('room', $_GET['room']);
        $this->ECMView->assign('rooms', $rooms);

        $this->ECMView->assign('date_from', $date_from);
        $this->ECMView->assign('date_to', $date_to);
        $this->ECMView->assign('transactions', $transactions);
        $this->ECMView->assign('mode', 'all');
        echo $this->ECMView->render('bookingList.phtml');
    }

	public function bookingListPrintAction()
	{
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        ini_set('display_errors', 'on');

        if ($_GET['date_from'] != '') {
            $date_from = $_GET['date_from'];
            $date_to = $_GET['date_to'];
            $l = explode('/', $date_from);
            $sel_date_from = $l[2] . '-' . $l[1] . '-' . $l[0];
            $l = explode('/', $date_to);
            $sel_date_to = $l[2] . '-' . $l[1] . '-' . $l[0];
        } else {
            $date_from = date('01/m/Y');
            $date_to = date('t/m/Y');
            $sel_date_from = date('Y-m-01');
            $sel_date_to = date('Y-m-t');
        }
        $user_id = $_SESSION['accID'];
        if (trim($user_id) == '') {
            $user_id = '-';
        }

        $roomTransaction = new RoomTransaction();
        $transactions = $roomTransaction->getAllTransaction($sel_date_from, $sel_date_to, $_GET['room'], $user_id);
        //var_dump($transactions);

        $room = new Room();
        $rooms = $room->getAllRoom();
        $this->ECMView->assign('room', $_GET['room']);
        $this->ECMView->assign('rooms', $rooms);

        $this->ECMView->assign('date_from', $date_from);
        $this->ECMView->assign('date_to', $date_to);
        $this->ECMView->assign('transactions', $transactions);
        $this->ECMView->assign('mode', 'self');
        echo $this->ECMView->render('bookingListPrint.phtml');
	}
	
	public function bookingAllPrintAction()
	{
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        ini_set('display_errors', 'on');

        if ($_GET['date_from'] != '') {
            $date_from = $_GET['date_from'];
            $date_to = $_GET['date_to'];
            $l = explode('/', $date_from);
            $sel_date_from = $l[2] . '-' . $l[1] . '-' . $l[0];
            $l = explode('/', $date_to);
            $sel_date_to = $l[2] . '-' . $l[1] . '-' . $l[0];
        } else {
            $date_from = date('d/m/Y');
            $date_to = date('d/m/Y');
            $sel_date_from = date('Y-m-d');
            $sel_date_to = date('Y-m-d');
        }

        $roomTransaction = new RoomTransaction();
        $transactions = $roomTransaction->getAllTransaction($sel_date_from, $sel_date_to, $_GET['room']);
        //var_dump($transactions);

        $room = new Room();
        $rooms = $room->getAllRoom();
        $this->ECMView->assign('room', $_GET['room']);
        $this->ECMView->assign('rooms', $rooms);

        $this->ECMView->assign('date_from', $date_from);
        $this->ECMView->assign('date_to', $date_to);
        $this->ECMView->assign('transactions', $transactions);
        $this->ECMView->assign('mode', 'all');
        echo $this->ECMView->render('bookingListPrint.phtml');
	}
	
    public function printAction()
    {
        $id = $_GET['id'];
        $roomTransaction = new RoomTransaction();
        $trans = $roomTransaction->getTransactionById($id);
        $this->ECMView->assign('trans', $trans);

        $food_trans = $roomTransaction->getFoodTransaction($id);
        $this->ECMView->assign('food_trans', $food_trans);

        $account = $roomTransaction->getAccountRole($trans['F_USERID']);
        $this->ECMView->assign('account', $account);

        $this->ECMView->assign('foodTransaction', $data);

        echo $this->ECMView->render('print.phtml');
    }

    public function cancelAction()
    {
        $id = $_GET['id'];
        $roomTransaction = new RoomTransaction();
        $trans = $roomTransaction->cancelTransaction($id);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function validateAction()
    {
        $room = $_GET['room'];
        $date = $_GET['date'];
        $start = $_GET['start'];
        $end = $_GET['end'];
        $trans_id = $_GET['trans_id'];

        //echo $date, $start, $end;
        $roomTransaction = new RoomTransaction();
        list($d, $m, $y) = explode('/', $date);
        $result = $roomTransaction->validate($room, $y . '-' . $m . '-' . $d, $start, $end, $trans_id);
        //var_dump($result);
        if ($result) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function roomManageAction()
    {
        $user_id = $_SESSION['accID'];
        if (trim($user_id) == '') {
            $user_id = '-';
        }

        $room = new Room();
        $rooms = $room->getAllRoom();
        $this->ECMView->assign('rooms', $rooms);
        echo $this->ECMView->render('roomManage.phtml');
    }

    public function roomDetailAction()
    {
        $room_id = $_GET['id'];

        $room = new Room();

        $room_data = $room->getById($room_id);
        $catagory = $room->getCatagory();
        //echo $room_id;

        $this->ECMView->assign('room', $room_data);
        $this->ECMView->assign('catagory', $catagory);
        $output = $this->ECMView->render('roomDetail.phtml');
        echo $output;
    }

    public function editroomAction()
    {
        $room = new Room();
        $result = $room->save($_POST);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function deleteRoomAction()
    {
        $id = $_GET['id'];
        $room = new Room();
        if ($room->isUse($id)) {
            echo "
<script>
alert('��ͧ�ա����ҹ���� �������öź��');
window.location = '" . $_SERVER['HTTP_REFERER'] . "';
</script>
            ";
        } else {
            $room->delete($id);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    public function foodManageAction()
    {
        $user_id = $_SESSION['accID'];
        if (trim($user_id) == '') {
            $user_id = '-';
        }

        $food = new Food();
        $foods = $food->getAll();
        $this->ECMView->assign('foods', $foods);
        echo $this->ECMView->render('foodManage.phtml');
    }

    public function foodDetailAction()
    {
        $food_id = $_GET['id'];
        if ($food_id != 'NEW') {
            $food = new Food();
            $food_data = $food->getById($food_id);
        } else {
            $food_data = array('f_rowid_food' => 'NEW');
        }

        //echo $room_id;

        $this->ECMView->assign('food', $food_data);
        $output = $this->ECMView->render('foodDetail.phtml');
        echo $output;
    }

    public function editFoodAction()
    {
        $food = new Food();
        $result = $food->save($_POST);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function deleteFoodAction()
    {
        $id = $_GET['id'];
        $food = new Food();
        if ($food->isUse($id)) {
            echo "
<script>
alert('�ա����ҹ���� �������öź��');
window.location = '" . $_SERVER['HTTP_REFERER'] . "';
</script>
            ";
        } else {
            $food->delete($id);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
	
	public function categoryManageAction()
	{
        $user_id = $_SESSION['accID'];
        if (trim($user_id) == '') {
            $user_id = '-';
        }

        $room = new Room();
        $category = $room->getCatagory();
        $this->ECMView->assign('category', $category);
        echo $this->ECMView->render('categoryManage.phtml');
	}

    public function categoryDetailAction()
    {
        $category_id = $_GET['id'];
        if ($category_id != 'NEW') {
            $room = new Room();
            $category_data = $room->getCategoryById($category_id);
        } else {
            $category_data = array('f_rowid_subcatagories' => 'NEW');
        }

        //echo $room_id;
		//var_dump($category_data);
        $this->ECMView->assign('category', $category_data);
        $output = $this->ECMView->render('categoryDetail.phtml');
        echo $output;
    }

    public function editCategoryAction()
    {
        $room = new Room();
        $result = $room->saveCategory($_POST);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function deleteCategoryAction()
    {
        $id = $_GET['id'];
        $room = new Room();
        if ($room->isUseCategory($id)) {
            echo "
<script>
alert('�ա����ҹ���� �������öź��');
window.location = '" . $_SERVER['HTTP_REFERER'] . "';
</script>
            ";
        } else {
            $room->deleteCategory($id);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

	public function statisticAction()
	{

        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        ini_set('display_errors', 'on');

        if ($_GET['date_from'] != '') {
            $date_from = $_GET['date_from'];
            $date_to = $_GET['date_to'];
            $l = explode('/', $date_from);
            $sel_date_from = $l[2] . '-' . $l[1] . '-' . $l[0];
            $l = explode('/', $date_to);
            $sel_date_to = $l[2] . '-' . $l[1] . '-' . $l[0];
        } else {
            $date_from = date('01/01/Y');
            $date_to = date('31/12/Y');
            $sel_date_from = date('Y-01-01');
            $sel_date_to = date('Y-12-31');
        }
		
//		echo $date_from;
//		echo $date_to;


        $roomTransaction = new RoomTransaction();
        $transaction = $roomTransaction->getStatistic($sel_date_from, $sel_date_to);
		//var_dump($transactions);
        $this->ECMView->assign('transaction', $transaction);

		
        $room = new Room();
        $rooms = $room->getAllRoom();
        $this->ECMView->assign('room', $_GET['room']);
        $this->ECMView->assign('rooms', $rooms);

        $this->ECMView->assign('date_from', $date_from);
        $this->ECMView->assign('date_to', $date_to);
        $output = $this->ECMView->render('statistic.phtml');
        echo $output;
	}
}
