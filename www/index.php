<?php

ini_set('display_errors',1); error_reporting(E_ALL);

$file_hostname_assignments = "hostnames.assign";

$new_hostname_prefix = "rpii";

$action = (isset($_GET['action']) ? $_GET['action'] : "noaction");
$debug = (isset($_GET['debug']) ? $_GET['debug'] : false);


if($debug)
	echo "Reading hostname assignments to memory\n";

list($hostnames,$hostid) = restore_host_assignments();

switch($action){
	case "register":
		if($debug){
			echo "Reading hostname assignments to memory\n";
			echo "Registering new node\n";
		}
		$macaddr = $_GET['macaddr'];
		$exists = false;
		foreach($hostnames as $hostname){
			$mac = $hostname["mac"];
			if($mac == $macaddr){
				$new_hostname = $hostname["hostname"];
				$exists = true;
			}
		}

		if(!$exists){
			$new_hostname = $new_hostname_prefix. sprintf("%02d", $hostid);
			$new_assignment = array("mac" => $macaddr, "hostname" => $new_hostname, "hostid" => $hostid);
			array_push($hostnames, $new_assignment);
			$hostid++;
		}

		if($debug){
			echo "macaddr: $macaddr\n";
			echo "New hostid: $hostid\n";
			echo "new hostname: $new_hostname\n";
		} else {
			echo "$new_hostname\n";
		}
	break;

	default:
		die("No valid action specified\n");
	break;
}

if($debug){
	print_r($hostnames);
}


save_host_assignments($hostnames, $hostid);
function restore_host_assignments(){
	global $file_hostname_assignments;
	$arr = json_decode(file_get_contents($file_hostname_assignments), true);
	return array($arr["hosts"],$arr["hostid"]);
}

function save_host_assignments($arr, $hostid){
	global $file_hostname_assignments;
	$arr = array("hosts" => $arr, "hostid" => $hostid);
	file_put_contents($file_hostname_assignments ,json_encode($arr));
	return 1;
}

?>
