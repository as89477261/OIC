window.moveTo(0,0);
if (document.all) 
{
	top.window.resizeTo(screen.availWidth,screen.availHeight);
}
else if (document.layers||document.getElementById) 
{
	if (top.window.outerHeight<screen.availHeight||top.window.outerWidth<screen.availWidth)
	{
		top.window.outerHeight = screen.availHeight;
		top.window.outerWidth = screen.availWidth;
	}
}

            function openRoomBooking(date)
            {
                $.modal({
                    url: '/ECM/room/booking/?date='+date,
                    //content: '',
                    title: '�ͧ��ͧ��Ъ�� ',
                    maxWidth: 800,
                    width: 600,
                    height: 560
//                    , buttons: {
//                        '�ѹ�֡': function(win) {
//                            if(validate_form()){
//                                alert('ok');
//                            }else{
//                                alert('fail');
//                            }
                            //win.closeModal();
//                        },
//                        '¡��ԡ': function(win) {
//                            win.closeModal();
//                        }
//                    }
                });
            }

            function openRoomBookingDetail(id)
            {
                $.modal({
                    url: '/ECM/room/booking/?id='+id,
                    //content: '',
                    title: '��䢡�èͧ��ͧ��Ъ��',
                    maxWidth: 800,
                    width: 600,
                    height: 560//,
//                    buttons: {
//                        '<img src="/<?=$config ['appName']?>/reservation/images/icons/btn_print.gif" width="16" height="16"> �����': function(win) {
//                            win.closeModal();
//                        },
//                        '�ѹ�֡': function(win) {
//                            win.closeModal();
//                        },
//                        '¡��ԡ': function(win) {
//                            win.closeModal();
//                        }
//                    }
                });
            }
			
			

            function openRoomDetail(id, name)
            {
                $.modal({
                    url: '/ECM/room/room-detail/?id=' + id,
                    //content: '',
                    title: name,
                    maxWidth: 800,
                    width: 600,
                    height: 500,
                    buttons: {
                        '�Դ': function(win) {
                            win.closeModal();
                        }
                    }
                });
                //$(this).getModalWindow().loadModalContent('/<?php echo $config ['appName']?>/room/room-detail/?id=' + id);
            }
			
            function openFoodDetail(id, name)
            {
                $.modal({
                    url: '/ECM/room/food-detail/?id=' + id,
                    //content: '',
                    title: name,
                    maxWidth: 800,
                    width: 600,
                    height: 500,
                    buttons: {
                        '�Դ': function(win) {
                            win.closeModal();
                        }
                    }
                });
                //$(this).getModalWindow().loadModalContent('/<?php echo $config ['appName']?>/room/room-detail/?id=' + id);
            }
			
            function openCategoryDetail(id, name)
            {
                $.modal({
                    url: '/ECM/room/category-detail/?id=' + id,
                    //content: '',
                    title: name,
                    maxWidth: 800,
                    width: 600,
                    height: 500,
                    buttons: {
                        '�Դ': function(win) {
                            win.closeModal();
                        }
                    }
                });
                //$(this).getModalWindow().loadModalContent('/<?php echo $config ['appName']?>/room/room-detail/?id=' + id);
            }