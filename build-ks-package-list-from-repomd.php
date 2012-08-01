<?php
/**
 * Script to query through comps.xml or repomd.xml and generate a 
 * kickstart-ready package list
 *
 * Author: Rafael Felix Correa <rafael.felix@rf4solucoes.com.br>
 */

/*function show_usage() {
	echo
	
	--help         prints this help message
	--uservisible  queries repomd.xml groups by user visibility (accepted values: 0 or 1)
	--default
	--packagetype=mandatory|default|conditional|all queries repomd.xml packages by packagetype
}*/

// package type mandatory | default | conditional
// group user visibility: true | false
// group default: true | false
// 

$repomd_path = !empty($argv[1]) ? $argv[1] : null;
if (is_null($repomd_path)) {
	echo "usage: $argv[0] repomd_path\n";
	exit(1);
}

$desired_groups = array('Base', 'Core');

echo "%packages\n";
$repomd = simplexml_load_file($repomd_path);
foreach($repomd->group as $group) {
	//if((string)$group->default == 'true') {
	if (in_array((string)$group->name, $desired_groups)) {
		//echo "@{$group->name}\n";
		foreach($group->packagelist->packagereq as $packagereq) {
			if ((string)$packagereq['type'] == 'mandatory') {
				//echo "\t Package: ".$packagereq.' - '.$packagereq['type']."\n";
				echo $packagereq."\n";
			}
		}
	}
}
echo "%end\n";
exit(0);
?>