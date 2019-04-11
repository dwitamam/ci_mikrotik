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
        if($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

            $this->routerosapi->write(':put /ip/firewall/mangle/getall');

            $users = $this->routerosapi->read();

            $this->routerosapi->disconnect();

            $total_results = count($users);
            if($total_results > 0){
                $data['total_results'] = $total_results;
                $table = '<table class="table table-bordered table-hover">';
                $table .= '<thead>';
                $table .= '<tr>';
				$table .= '<th class="text-center">No.</th>';
                $table .= '<th class="text-center">Chain</th>';
                $table .= '<th class="text-center">Action</th>'; 
                $table .= '<th class="text-center">Src Address</th>';
                $table .= '<th class="text-center">Address List</th>';
				$table .= '<th class="text-center">Content</th>';
				$table .= '<th class="text-center">Disabled</th>';
				$table .= '<th class="text-center">#</th>';
                $table .= '</tr>';
                $table .= '</thead>';
                $i = 1;
                foreach ($users as $user){	
					$table .= '<tr>';
					$table .= '<td class="col-md-1 text-center">'.$i.'.</td>';
					$table .= '<td class="col-md-1 text-center">'.$user['chain'].'</td>';
					$table .= '<td class="col-md-2 text-center">'.$user['action'].'</td>';
                    $table .= '<td class="col-md-1 text-center">'.$user['src-address'].'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$user['address-list'].'</td>';
					$table .= '<td class="col-md-1 text-center">'.$user['content'].'</td>';
					$table .= '<td class="col-md-1 text-center">'.$user['disabled'].'</td>';
						
					$table .= '<td>';
					$table .= anchor('blokSitus/update/'.$user['.id'],'<button type="button" class="btn btn-success btn-sm">
					<span class="glyphicon glyphicon-edit"></span> Update</button>').' ';
					$table .= anchor('blokSitus/remove/'.$user['.id'],'<button type="button" class="btn btn-danger btn-sm">
					<span class="glyphicon glyphicon-minus"></span> Remove</button>').' ';
					if ($user['disabled'] == 'false'){
						$table .= anchor('blokSitus/disable/'.$user['.id'],'<button type="button" class="btn btn-warning btn-sm">
						                                   <span class="glyphicon glyphicon-remove"></span> Disable</button>');
					}else{
						$table .= anchor('blokSitus/enable/'.$user['.id'],'<button type="button" class="btn btn-info btn-sm">
						                                    <span class="glyphicon glyphicon-ok"></span> Enable</button>');
					}
					$table .= '</td>';				
					$table .= '</tr>';
					$i++;
				}
				$table .= '</table>';
				$data['table'] = $table;
            }
            $data['container'] = 'mangle/mangle';	
			$data['link'] = array('link_tambah' => anchor('blokSitus/add', '<button type="button" class="btn btn-primary btn-sm">
			<span class="glyphicon glyphicon-plus"></span> Tambah</button>'));
			$this->load->view('template', $data);
        }
    }
	
}
