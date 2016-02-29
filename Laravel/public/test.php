<?php
$items = array(1,2,3,4,5,6,7,8,9,10);

$start = microtime();
for($x=0;$x<100000;$x++){
    for($i=0;$i<count($items);$i++)
        {
          $j = 100381*$i;
        }
}

echo microtime()-$start;
