<?php

use yii\helpers\Url;


ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );

set_time_limit( 0 );



//print '<pre>';
//print_r($num);
//print '</pre>';

foreach ( $num as $v ) {
	//print $v['part'] . '<br>';
	foreach ( $v["attachs"] as $k => $attach ) {
//		echo( $attach["file"] );
//		$html = file_get_html($attach['file']);
		echo $html;
	}
}

?>

<div class="mailparsing-default-index">
	<p>
		<a href="<?= Url::to( [ 'default/get-mail' ] ) ?>">Get mail</a>
	</p>
</div>
