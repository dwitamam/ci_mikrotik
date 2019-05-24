<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BwController extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('routerosapi');
    }

    var $hostname = '192.168.40.1';
    var $username = 'admin';
    var $password = '';

    

    public function index(){

        $a=0;
        $b=0;

        $this->load->library('session');

        if($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

            //$this->routerosapi->write(':put [/ip/hotspot/user/get/faiz/bytes-in]');
            //$this->routerosapi->write('/ip/address/getall');
            //$this->routerosapi->write('/ip/hotspot/host/print/packets/getall');
            $this->routerosapi->write('/queue/simple/getall');

            $users = $this->routerosapi->read();

            $this->routerosapi->disconnect();

            $total_results = count($users);

            $maxTx;
            $maxRx;

            if($total_results > 0){
                $data['total_results'] = $total_results;
                $table = '<table class="table table-bordered table-hover">';
                $table .= '<thead>';
                $table .= '<tr>';
                $table .= '<th class="text-center">No.</th>';
                $table .= '<th class="text-center">transfer rate</th>';
                $table .= '<th class="text-center">receive rate</th>';
                $table .= '<th class="text-center">ip target</th>';
                $table .= '<th class="text-center">terbesar tx</th>';
                $table .= '<th class="text-center">terbesar rx</th>';

                $table .= '</tr>';
                $table .= '</thead>';
                $i = 1;

                foreach ($users as $user){
                    $terbesarTx = 0;
                    $terbesarRx = 0;
                    $namaIp = $user['target'];
                    $var1 = "";
                    $var2 = "";
                    if(!empty($_SESSION[$namaIp])){
                        $var1 = $namaIp.'tx';
                        $var2 = $namaIp.'rx';
                    }else{
                        $_SESSION[$namaIp.'tx'] = 0;
                        $_SESSION[$namaIp.'rx'] = 0;
                    }

                    if(!empty($_SESSION[$var1]) or !empty($_SESSION[$var2])){
                        $terbesarTx = $_SESSION[$var1];
                        $terbesarRx = $_SESSION[$var2];
                    }else{
                        $_SESSION[$var1] = 0;
                        $_SESSION[$var2] = 0;
                    }
            
                    $rate = $user['rate'];
                    $split = explode("/", $rate);
                    $tx = $split[0];
                    $rx = $split[1];
                    $txInt = (int)$tx;
                    $rxInt = (int)$rx;

                    $usera = $rxInt / 1000;
                    $userb = $txInt / 1000;
                    

                    if($terbesarTx<$usera){
                        $terbesarTx = $usera;
                        $_SESSION[$var1] = $usera;
                    }


                    if($terbesarRx<$userb){
                        $terbesarRx = $userb;
                        $_SESSION[$var2] = $userb;
                    }

                    if($a<$_SESSION[$var1]){
                        $a = $_SESSION[$var1];
                    }


                    if($b<$userb){
                        $b= $_SESSION[$var2];
                    }


					$table .= '<tr>';

                    $table .= '<td class="col-md-1 text-center">'.$i.'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$usera.' kb'.'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$userb.' kb'.'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$user['target'].'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$a.'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$b.'</td>';
					

					$table .= '</td>';				
					$table .= '</tr>';
					$i++;
				}
				$table .= '</table>';
				$data['table'] = $table;
            }
            $data['container'] = 'bandwidth/bandwidth';	

			$this->load->view('template', $data);
        }
    }

    public function torch(){
        if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
            $srcAddresss='192.168.50.254';
            $interfacee = 'ether5';
            $this->routerosapi->write('/tool/torch', false);
            $this->routerosapi->write('ether5', false);
            $bacas = $this->routerosapi->read();
            $this->routerosapi->disconnect();
            $total_resultss = count($bacas);
            foreach($bacas as $baca){
                print_r($baca['tx']);
            }
        }
    }

    public function hehe(){
        if($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
            //$srcAddress = '192.168.33.1';
            /*
            $interface = 'ether5';
            $this->routerosapi->write('/tool/torch', false);
            $this->routerosapi->write('=src-address= 192.168.50.253', false);
            $this->routerosapi->write('=interface= ether1', false);
            $reads = $this->routerosapi->read();
            $this->routerosapi->disconnect();
            $total_reads = count($reads);
            if($total_reads > 0){
                print_r($reads);
                
            }
            */



            $this->routerosapi->write('/queue/simple/getall');
            $reads = $this->routerosapi->read();
            foreach($reads as $read){
                $rate = $read['rate'];
                $split = explode("/", $rate);
                $rx = $split[0];
                $tx = $split[1];

                echo '<table style="border: 1px solid black; width: 100%;">';
                echo '<tr>';
                echo '<td>', $rx ,'</td>';
                echo '<td>', $tx ,'</td>';
                echo '<td>', $read['target'];
                echo '</tr>';
                echo '</table>';
            }
            
        }
    }

    public function tulisFile(){
        $this->load->helper('file');
        $this->load->helper('url');
        
        $data = 'lima';
        $path = base_url().'asset/data/data.txt';
        $path1 = 'helpers/data.txt';
        if ( ! write_file($path, $data))
        {
                echo 'Unable to write the file';
        }
        else
        {
                echo 'File written!';
        }
    }
    
    public function updateFile(){
        $angkaNotepad;
        $this->load->helper('file');
        $baca = file('data/data.txt');
        print_r($baca);
        echo $baca;
    }

    public function sesi(){
        $this->load->library('session');
        $dataRx;
        $dataTx;
        $teks = 'kedok';
        $_SESSION['192.168.50.253/32'] = $teks;
        $hehe = $_SESSION['192.168.50.253/32'];
        echo $hehe;
    }

    public function gratis(){
        $this->load->library('session');
        echo $_SESSION['text'];
    }
}
