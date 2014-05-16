<?php
$m = ManagerUser::getMessage();
if($m == true)
foreach ($m as $key => $value) {
	echo $value;
}
?>