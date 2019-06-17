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
        $this->load->library('session');

        if($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
            $this->routerosapi->write('/queue/simple/getall');
            $users = $this->routerosapi->read();
            $this->routerosapi->disconnect();
            $total_results = count($users);
            if($total_results > 0){
                $data['total_results'] = $total_results;
                $table = '<table class="table table-bordered table-hover">';
                $table .= '<thead>';
                $table .= '<tr>';
                $table .= '<th class="text-center">No.</th>';
                $table .= '<th class="text-center">transfer rate</th>';
                $table .= '<th class="text-center">receive rate</th>';
                $table .= '<th class="text-center">ip target</th>';
                $table .= '<th class="text-center">tx max</th>';
                $table .= '<th class="text-center">rx max</th>';

                $table .= '</tr>';
                $table .= '</thead>';
                $i = 1;



                foreach ($users as $user){
                    // untuk penamaan session
                    $arrayIp = $user['target'];
                    $arrayIpTextTx = $arrayIp.'tx';
                    $arrayIpTextRx = $arrayIp.'rx';

                    // if untuk buat session
                    if(!isset($_SESSION[$arrayIpTextTx]) || !isset($_SESSION[$arrayIpTextRx])){
                        $_SESSION[$arrayIpTextTx] = 0; 
                        $_SESSION[$arrayIpTextRx] = 0;
                    }
                    
                    // untuk pengambilan tx rx dari rate 
                    $rate = $user['rate'];
                    $split = explode("/", $rate);
                    $tx = $split[0];
                    $rx = $split[1];
                    $txInt = (int)$tx;
                    $rxInt = (int)$rx;
                    $txV = $txInt / 1000;
                    $rxV = $rxInt / 1000;
                    $txMax = 0;
                    $rxMax = 0;               

                    // if untuk perbandingan tx rx
                    if($txMax < $txV){
                        $txMax = $txV;
                        if($_SESSION[$arrayIpTextTx] < $txMax){
                            $_SESSION[$arrayIpTextTx] = $txMax;
                        }
                    }
                    if($rxMax < $rxV){
                        $rxMax = $rxV;
                        if($_SESSION[$arrayIpTextRx] < $rxMax){
                            $_SESSION[$arrayIpTextRx] = $rxMax;
                        }
                    }

					$table .= '<tr>';

                    $table .= '<td class="col-md-1 text-center">'.$i.'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$rxV.' kb'.'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$txV.' kb'.'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$user['target'].'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$_SESSION[$arrayIpTextRx].'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$_SESSION[$arrayIpTextTx].'</td>';
					

					$table .= '</td>';				
					$table .= '</tr>';
					$i++;
				}
				$table .= '</table>';
				$data['table'] = $table;
            }
            $data['container'] = 'bandwidth/bandwidth';	

			$this->load->view('template1', $data);
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
    
    public function cek(){
        $this->load->library('session');

        if($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
            $this->routerosapi->write('/queue/simple/getall');
            $values = $this->routerosapi->read();
            $this->routerosapi->disconnect();

            foreach($values as $value){
                // untuk penamaan session
                $arrayIp = $value['target'];
                $arrayIpTextTx = $arrayIp.'tx';
                $arrayIpTextRx = $arrayIp.'rx';

                // if untuk buat session
                if(!isset($_SESSION[$arrayIpTextTx]) || !isset($_SESSION[$arrayIpTextRx])){
                    $_SESSION[$arrayIpTextTx] = 0; 
                    $_SESSION[$arrayIpTextRx] = 0;
                }
                
                // untuk pengambilan tx rx dari rate 
                $rate = $value['rate'];
                $split = explode("/", $rate);
                $tx = $split[0];
                $rx = $split[1];
                $txInt = (int)$tx;
                $rxInt = (int)$rx;
                $txV = $txInt / 1000;
                $rxV = $rxInt / 1000;
                $txMax = 0;
                $rxMax = 0;

                echo 'hehe'.'<br>';                

                // if untuk perbandingan tx rx
                if($txMax < $txV){
                    $txMax = $txV;
                    if($_SESSION[$arrayIpTextTx] < $txMax){
                        $_SESSION[$arrayIpTextTx] = $txMax;
                    }
                }
                if($rxMax < $rxV){
                    $rxMax = $rxV;
                    if($_SESSION[$arrayIpTextRx] < $rxMax){
                        $_SESSION[$arrayIpTextRx] = $rxMax;
                    }
                }

                echo 'tx per detik '.$txV.'<br>';
                echo 'tx max '.$_SESSION[$arrayIpTextTx].'<br>';
                echo 'rx per detik '.$rxV.'<br>';
                echo 'rx max '.$_SESSION[$arrayIpTextRx].'<br>';

                //echo $_SESSION[$arrayIpTextTx].'<br>';
                //echo $_SESSION[$arrayIpTextRx].'<br>';
                echo '<hr>';
            }
        }


        


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
