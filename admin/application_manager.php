<?php 
require_once 'check_login.php';
$roles_required = Array(
    2,    //管理员
    4     //应用仓库管理员
);
require_once 'role_check.php';

$page_title = "应用产品基本信息管理！"; 
require_once '../res/header.php';
require_once '../res/navigator.php';
$link = "application_manager_offline.php";
$link_name = "已下线应用产品管理";
require_once '../res/righttop_link.php';

$page_fetch_limit = 20;
$page_index_max = 10;
?>

<?php 
require_once '../proxy/class.databaseproxy.php';
$column_application = DatabaseProxy::DB_COLUMN_APPLICATION;
$column_puup = DatabaseProxy::DB_COLUMN_APPLICATION_PUUP_POINT;
$column_serial = DatabaseProxy::DB_COLUMN_APPLICATION_SERIAL;
$column_category_serial = DatabaseProxy::DB_COLUMN_CATEGORY_SERIAL;
$column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
$column_package = DatabaseProxy::DB_COLUMN_APPLICATION_PACKAGE;
$column_icon = DatabaseProxy::DB_COLUMN_APPLICATION_IOCN;
$column_producer = DatabaseProxy::DB_COLUMN_APPLICATION_PRODUCER;
$column_description = DatabaseProxy::DB_COLUMN_APPLICATION_DESCRIPTION;
$column_notes = DatabaseProxy::DB_COLUMN_APPLICATION_NOTES;
$column_register_date = DatabaseProxy::DB_COLUMN_APPLICATION_REGISTER_DATE;
$column_group_serial = DatabaseProxy::DB_COLUMN_GROUP_SERIAL;
$column_group = DatabaseProxy::DB_COLUMN_GROUP;
$column_apkfile_vercode = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_CODE;
$column_promotion = DatabaseProxy::DB_TABLE_PROMOTIONS;


$session_name = 'application';
$default_sort = $column_application;
$order = 0;
$reorder = 1;
$cur_page = 1;
$sort = $default_sort;
require 'sortorder.php';

?>


					<div align="center">
						<table width="100%" border="3">
							<tr>
								<td width="48">
									<div class="column_name">图标</div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_application&order=$reorder"; ?>">应用名称</a></div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_category_serial&order=$reorder"; ?>">应用类型</a></div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_puup&order=$reorder"; ?>">飞扬指数</a></div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_package&order=$reorder"; ?>">应用包名</a></div>
								</td>
								<td>
									<div class="column_name">当前版本</div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_producer&order=$reorder"; ?>">出品人</a></div>
								</td>
								<td>
									<div class="column_name"><a href="<?php echo $_SERVER['PHP_SELF']."?sort=$column_register_date&order=$reorder"; ?>">登记日期</a></div>
								</td>
								<td>
									<div class="column_name">说明</div>
								</td>
								<td></td>
							</tr>

<?php
require_once '../utilities/class.applicationmanager.php';
$mgr = new ApplicationManager();

$limit = $page_fetch_limit;
$online = 1;
$total = $mgr->getTotal($online);
$num_pages = ceil($total/$limit);
if($cur_page > $num_pages){
    $cur_page = $num_pages;
}

$array = $mgr->fetchApplications($sort, $cur_page, $limit, $order,$online);

require_once '../res/url_conf.php';

require_once '../utilities/class.apkfilemanager.php';
$apkMgr = new ApkfileManager();

foreach ($array as $row){
    
    $apkfile = $apkMgr->getApkFileByApplication($row["$column_serial"]+0);
    if ($apkfile==FALSE){
        $vercode = "";
    }
    else{
        $vercode = $apkfile["$column_apkfile_vercode"];
    }
    
?>

							<tr>
								<td width="48">
									<img src="<?php echo $path_apkicons.$row["$column_icon"]?>" width="48" height="48" >
								</td>
								<td >
									<div class="column_value"><?php echo $row["$column_application"];?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $row["$column_category"];?></div>
								</td>
								<td >
									<div class="column_value"><?php echo $row["$column_puup"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_package"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $vercode;?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_producer"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_register_date"];?></div>
								</td>
								<td>
									<div class="column_value"><?php echo $row["$column_description"];?></div>
								</td>
								<td>
								
<?php 
    echo  '<div class="column_value_center"><a href="application_edit.php'
            .'?serial='.$row["$column_serial"]
            .'&application='.$row["$column_application"]
            .'&puup_point='.$row["$column_puup"]
            .'&category_serial='.$row["$column_category_serial"]
            .'&icon='.$row["$column_icon"]
            .'&package='.$row["$column_package"]
            .'&producer='.$row["$column_producer"]
            .'&description='.$row["$column_description"]
            .'">修改</a></div>';
?>
								</td>
							</tr>
    
<?php     
}
require_once '../utilities/class.utilities.php';
$page_links = Utilities::generate_page_links($_SERVER['PHP_SELF'], $sort, $order, $cur_page, $num_pages, $page_index_max);

?>
						</table>
					</div>
					<div align="right">
						<?php echo $page_links;?>
					</div>

<?php 
require_once '../res/footer.php';
?>
