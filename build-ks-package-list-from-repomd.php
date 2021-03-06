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

//$desired_groups = array('Base', 'Core');

echo "%packages\n";
$repomd = simplexml_load_file($repomd_path);
$groups = $repomd->xpath('/comps/group[default=\'true\']');

foreach($groups as $group) {
	echo "#@{$group->name}\n";
	
	// list all mandatory packages for this group
	echo "##{$group->name}: mandatory\n";
	$mandatory = $group->xpath('packagelist/packagereq[@type=\'mandatory\']');
	foreach($mandatory as $m) {
		echo $m."\n";
	}
	
	// list all default packages for this group
	echo "##{$group->name}: default\n";
	$default = $group->xpath('packagelist/packagereq[@type=\'default\']');
	foreach($default as $d) {
		echo "-$d\n";
	}
	
	// list all optional packages for this group
	echo "##{$group->name}: optional\n";
	$optional = $group->xpath('packagelist/packagereq[@type=\'optional\']');
	foreach($optional as $o) {
		echo "-$o\n";
	}
	
	// list all conditional packages for this group
	echo "##{$group->name}: conditional\n";
	$conditional = $group->xpath('packagelist/packagereq[@type=\'conditional\']');
	foreach($conditional as $c) {
		echo "-$c\n";
	}
}
echo "%end\n";
exit(0);
?>