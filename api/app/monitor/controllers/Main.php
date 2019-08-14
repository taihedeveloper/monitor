<?php
/**
 * @name Main_Controller
 * @desc 主控制器,也是默认控制器
 * @author chengbiao(chengbiao@taihe.com)
 */
class Controller_Main extends Ap_Controller_Abstract {
	public $actions = array(
        'itemmodify' => 'actions/api/ItemModify.php',
        'itemshow' => 'actions/api/ItemShow.php',
        'loginuser' => 'actions/api/LoginUser.php',
        'itemdetail' => 'actions/api/ItemDetail.php',
		'runrecord' => 'actions/api/RunRecord.php',
		'alertrecord' => 'actions/api/AlertRecord.php',
		'alertrecorddetail' => 'actions/api/AlertRecordDetail.php',
		'productline' => 'actions/api/ProductLine.php',
		'certificatemanage' => 'actions/api/CertificateManage.php',
		'certificatemember' => 'actions/api/CertificateMember.php',
		'alertmanage' => 'actions/api/AlertManage.php',
		'alertmember' => 'actions/api/AlertMember.php',
		'obtaindata' => 'actions/api/ObtainData.php',
		'robotacount' => 'actions/api/RobotAcount.php',
        'userinfo' => 'actions/api/UserInfo.php',
	);
}
