<?php

namespace App;

class Fujitsu {
    
    public $community;
    public $mib_base;
    public $host;
    public $interfaces = [
        "1" => [
            "1" => ["info" => ["name" => "C1", "type" => "qsfp"]],
            "2" => ["info" => ["name" => "C2", "type" => "qsfp"]],
            "3" => ["info" => ["name" => "E1", "type" => "cfp"]],
            "4" => ["info" => ["name" => "E2", "type" => "cfp"]],
            "5" => ["info" => ["name" => "C3", "type" => "qsfp"]],
            "6" => ["info" => ["name" => "C4", "type" => "qsfp"]],
        ],
        "2" => [
            "1" => ["info" => ["name" => "C1", "type" => "qsfp"]],
            "2" => ["info" => ["name" => "C2", "type" => "qsfp"]],
            "3" => ["info" => ["name" => "E1", "type" => "cfp"]],
            "4" => ["info" => ["name" => "E2", "type" => "cfp"]],
            "5" => ["info" => ["name" => "C3", "type" => "qsfp"]],
            "6" => ["info" => ["name" => "C4", "type" => "qsfp"]],
        ]
    ];


    public function __construct($community, $host){
        $this->community = $community;
        $this->host = $host;
        $this->mib_base = 'FSS-PM::pmEqptShelfSlotSubslotPortPm-recordTime-period-indexPm-data-value';
    }

    public function discoverInterfacesQsfp(){
        try {
            
            $arr = snmp2_real_walk($this->host, $this->community, $this->mib_base, 10000000, 3);
            $keys = array_keys($arr);
            $return = [];

            foreach ($arr as $key => $data) {

                $mib = explode('.', $key);

                $mib[4] = preg_replace('/\"/', '', $mib[4]);
                $mib[1] = preg_replace('/\"/', '', $mib[1]);
                $mib[2] = preg_replace('/\"/', '', $mib[2]);
                $mib[3] = preg_replace('/\"/', '', $mib[3]);

                $chassis = $mib[1];
                $slot = $mib[2];
                $sub_slot = $mib[3];

                if($this->interfaces[$slot][$mib[4]]["info"]["type"] == "qsfp"){
                    array_push($return, ["{#SLOT}" => $slot, "{#INTERFACE}" => $this->interfaces[$slot][$mib[4]]["info"]["name"], "{#PORTID}" => $mib[4]]);
                }
            }
        } catch (Exception $e) {

        }

        $return = array_unique($return, SORT_REGULAR);
        $return = array_values($return);

        return $return;
    }

    public function discoverInterfacesCfp(){
        try {
            
            $arr = snmp2_real_walk($this->host, $this->community, $this->mib_base, 10000000, 3);
            $keys = array_keys($arr);
            $return = [];

            foreach ($arr as $key => $data) {

                $mib = explode('.', $key);

                $mib[4] = preg_replace('/\"/', '', $mib[4]);
                $mib[1] = preg_replace('/\"/', '', $mib[1]);
                $mib[2] = preg_replace('/\"/', '', $mib[2]);
                $mib[3] = preg_replace('/\"/', '', $mib[3]);

                $chassis = $mib[1];
                $slot = $mib[2];
                $sub_slot = $mib[3];

                if($this->interfaces[$slot][$mib[4]]["info"]["type"] == "cfp"){
                    array_push($return, ["{#SLOT}" => $slot, "{#INTERFACE}" => $this->interfaces[$slot][$mib[4]]["info"]["name"], "{#PORTID}" => $mib[4]]);
                }
            }
        } catch (Exception $e) {

        }

        $return = array_unique($return, SORT_REGULAR);
        $return = array_values($return);

        return $return;
    }

    public function getParameter($hostname){

        $array_data = [
            "opticalPowerReceiveLane1","opticalPowerReceiveLane2",
            "opticalPowerReceiveLane3","opticalPowerReceiveLane4",
            "opticalPowerTransmitLane1","opticalPowerTransmitLane2",
            "opticalPowerTransmitLane3","opticalPowerTransmitLane4",
            "chromaticDispersion","opticalPowerReceive",
            "opticalPowerTransmit","opticalSignalNoiseRatio",
            "polarizationDependentLoss"
        ];

        try {

            $arr = snmp2_real_walk($this->host, $this->community, $this->mib_base, 10000000, 3);
            $keys = array_keys($arr);
            $i = 0;

            foreach ($arr as $key => $data) {

                $mib = explode('.', $key);

                $mib[4] = preg_replace('/\"/', '', $mib[4]);
                $mib[1] = preg_replace('/\"/', '', $mib[1]);
                $mib[2] = preg_replace('/\"/', '', $mib[2]);
                $mib[3] = preg_replace('/\"/', '', $mib[3]);

                $chassis = $mib[1];
                $slot = $mib[2];
                $sub_slot = $mib[3];

                if (in_array($mib[5], $array_data) && $mib[6] == "nearEnd" && $mib[8] == "a15-min" && $mib[9] == 0) {

                    $data = preg_replace('/STRING\:\ /', '', $data);

                    $sender = new \Disc\Zabbix\Sender('localhost', 10051);
                    $sender->addData($hostname, $mib[5].'['.$slot.','.$mib[4].']', $data);
                    $sender->send();
        
                }
       
                $i++;
            }
        } catch (Exception $e) {

        }
    }

    public function fecDiscover(){

        try {
            $arr = snmp2_real_walk($this->host, $this->community, "FSS-PM::pmInterfaceInterfacePm-recordTime-period-indexPm-data-value", 10000000, 3);
            $keys = array_keys($arr);
            $interfaces_index = [];

            foreach ($arr as $key => $data) {
                $mib = explode('.', $key);
                if($mib[2] == "preFECbitErrorRate"){
                    array_push($interfaces_index, $mib[1]);
                }
            }
        } catch (Exception $e) {

        }

        $interfaces_index = array_unique($interfaces_index, SORT_REGULAR);

        $return = [];
        foreach($interfaces_index as $index){
            $interface_name = snmpget($this->host, $this->community, "ifName.$index");
            array_push($return, ["{#INTERFACE}" => $interface_name, "{#INTERFACE_INDEX}" => $index]);
        }
        return $return;
    }



}