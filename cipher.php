<?php
	echo "<form action='' method='post'>";
	echo "<input type='text' name='text'>";
	echo "<input type='submit' name='sub' value='confirm'>";
	echo "</form>";

	define("PI",3.1415926);
	function Encrypt($str){
			return $str=$str+PI;
	}
	function Decrypt($str){
			return $str=$str-PI;
	}
	if($_POST[sub]){
		echo "encrypt password &nbsp;&nbsp;".Encrypt($_POST[text])."<br>";
		echo "pwd"."$pwd"."<br>";
		$_SESSION[pwd]=Encrypt($_POST[text]);
		echo "$_SESSION[pwd]"."<br>";
?>
	<a href='cipher.php?pwd=1'>decription password</a>
<?php
	}
	if(	($_GET[pwd])){
			echo "$_SESSION[pwd]";
			echo "decription &nbsp;&nbsp;".Decrypt($_SESSION[pwd]);
	}
?>