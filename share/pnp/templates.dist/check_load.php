<?php
#
# Copyright (c) 2006-2010 Joerg Linge (http://www.pnp4nagios.org)
# Copyright (c) 2023-2023 Sven Nierlein
# Plugin: check_load
#
$opt[1] = "--vertical-label Load -l0  --title \"CPU Load for $hostname / $servicedesc\" ";
#
#
#
$def[1]  = "";
foreach($this->DS as $k=>$v) {
    if($v['LABEL'] === "load1")  { $def[1] .= rrd::def("var1", $v["RRDFILE"], $v["DS"], "AVERAGE"); }
    if($v['LABEL'] === "load5")  { $def[1] .= rrd::def("var2", $v["RRDFILE"], $v["DS"], "AVERAGE"); }
    if($v['LABEL'] === "load15") { $def[1] .= rrd::def("var3", $v["RRDFILE"], $v["DS"], "AVERAGE"); }

    if($v['LABEL'] === "scaled_load1")  {
        $def[] = "";
        $def[2] .= rrd::def("var1", $v["RRDFILE"], $v["DS"], "AVERAGE");
    }
    if($v['LABEL'] === "scaled_load5")  { $def[2] .= rrd::def("var2", $v["RRDFILE"], $v["DS"], "AVERAGE"); }
    if($v['LABEL'] === "scaled_load15") { $def[2] .= rrd::def("var3", $v["RRDFILE"], $v["DS"], "AVERAGE"); }
}

if ($WARN[1] != "") {
   $def[1] .= "HRULE:$WARN[1]#FFFF00 ";
}
if ($CRIT[1] != "") {
   $def[1] .= "HRULE:$CRIT[1]#FF0000 ";
}
$def[1] .= rrd::area("var3", "#ff0000", "load 15") ;
$def[1] .= rrd::gprint("var3", array("LAST", "AVERAGE", "MAX"), "%6.2lf");
$def[1] .= rrd::area("var2", "#EA8F00", "Load 5 ") ;
$def[1] .= rrd::gprint("var2", array("LAST", "AVERAGE", "MAX"), "%6.2lf");
$def[1] .= rrd::area("var1", "#EACC00", "load 1 ") ;
$def[1] .= rrd::gprint("var1", array("LAST", "AVERAGE", "MAX"), "%6.2lf");

# add scaled load if available
if(count($def) == 2) {
    $opt[] = "--vertical-label \"Scaled Load\" -l0  --title \"Scaled CPU Load for $hostname / $servicedesc\" ";
    $WARN[] = "";
    $CRIT[] = "";
    if ($WARN[2] != "") {
       $def[2] .= "HRULE:$WARN[2]#FFFF00 ";
    }
    if ($CRIT[2] != "") {
       $def[2] .= "HRULE:$CRIT[2]#FF0000 ";
    }
    $def[2] .= rrd::area("var3", "#ff0000", "load 15") ;
    $def[2] .= rrd::gprint("var3", array("LAST", "AVERAGE", "MAX"), "%6.2lf");
    $def[2] .= rrd::area("var2", "#EA8F00", "Load 5 ") ;
    $def[2] .= rrd::gprint("var2", array("LAST", "AVERAGE", "MAX"), "%6.2lf");
    $def[2] .= rrd::area("var1", "#EACC00", "load 1 ") ;
    $def[2] .= rrd::gprint("var1", array("LAST", "AVERAGE", "MAX"), "%6.2lf");
}

?>
