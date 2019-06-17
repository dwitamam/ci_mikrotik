<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HistoryLog extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('routerosapi');
    }

    var $hostname = '192.168.40.1';
    var $username = 'admin';
    var $password = '';

    

    public function index(){
        if($fh = fopen('/var/log/alog.log', 'r')){
            $table = '<div class="container-fluid">';
            $table .= '<table class="table table-bordered table-hover table-responsive">';
            $table .= '<thead><br>';
            $table .= '<tr>';
            $table .= '<th style="width: 1%">Bulan</th>';
            $table .= '<th >Tanggal</th>';
            $table .= '<th>Jam</th>';
            $table .= '<th>Jenis Request</th>';
            $table .= '<th>Service</th>';
            $table .= '<th>IP Client</th>';
            $table .= '<th>Jenis Request</th>';
            $table .= '<th>Keterangan</th>';
            $table .= '</tr>';
            $table .= '</thead>';
            $table .= '<tbody>';
            while(!feof($fh)){
                $line = fgets($fh);
                $u = explode(" ", $line);
                $e = count($u);
                $table .= '<tr>';
                $table .= '<td>';
                $table .= $u[0];
                $table .= '</td>';   
                $table .= '<td>';
                $table .= $u[2];
                $table .= ' </td>';
                $table .= '<td>';
                $table .= $u[3];
                $table .= ' </td>';
                $table .= '<td>';
                $table .= $u[4];
                $table .= ' </td>';
                $table .= '<td>';
                $table .= $u[5];
                $table .= ' </td>';
                $table .= '<td>';
                $table .= $u[6];
                $table .= ' </td>';
                $table .= '<td>';
                $table .= $u[7];
                $table .= ' </td>';
                $table .= '<td>';
                $table .= $u[8];
                $table .= ' </td>';
                $ar = array(" ");
                for($i=6; $i<$e;$i++){
                    array_push($ar, $u[$i]);
                }
                $rt = implode(" ", $ar);
                $table .= '</tr>';
        } fclose($fh);
        }
        $data['table'] = $table;
        $data['container'] = 'log/log';
        $this->load->view('template1', $data);
    }


}
