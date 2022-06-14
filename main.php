#!/usr/bin/php
<?php

require '/opt/fujitsu/vendor/autoload.php';

use App\Fujitsu;

$paremeter = $argv[1];
$host = $argv[2];
$community = $argv[3];

switch($paremeter){
    case "llvqsfp":
        $fujitsu = new Fujitsu($community, $host);
        $interfaces = $fujitsu->discoverInterfacesQsfp();
        echo json_encode($interfaces, true);
        break;

    case "llvcfp":
        $fujitsu = new Fujitsu($community, $host);
        $interfaces = $fujitsu->discoverInterfacesCfp();
        echo json_encode($interfaces, true);
        break;

    case "fecDiscover":
        $fujitsu = new Fujitsu($community, $host);
        $interfaces = $fujitsu->fecDiscover();
        echo json_encode($interfaces, true);
        break;
            
    default:
        $hostname = $argv[4];
        $fujitsu = new Fujitsu($community, $host);
        $data = $fujitsu->getParameter($hostname);
        echo "Deu certo!";
        break;
}