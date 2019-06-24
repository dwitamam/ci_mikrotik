<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HotspotController extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('routerosapi');
    }

    var $hostname = '192.168.40.1';
    var $username = 'admin';
    var $password = '';

    public function index(){
        if($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

            $this->routerosapi->write('/ip/hotspot/user/getall');

            $users = $this->routerosapi->read();

            $this->routerosapi->disconnect();

            $total_results = count($users);
            if($total_results > 0){
                $data['total_results'] = $total_results;
                $table = '<table class="table table-bordered table-hover">';
                $table .= '<thead>';
                $table .= '<tr>';
				$table .= '<th class="text-center">No.</th>';
                $table .= '<th class="text-center">Server</th>';
                $table .= '<th class="text-center">Name</th>'; 

                $table .= '<th class="text-center">Profile</th>';
				$table .= '<th class="text-center">Uptime</th>';
                $table .= '</tr>';
                $table .= '</thead>';
                $i = 1;
                foreach ($users as $user){	
					$table .= '<tr>';
					$table .= '<td class="col-md-1 text-center">'.$i.'.</td>';
					$table .= '<td class="col-md-1 text-center">'.$user['server'].'</td>';
					$table .= '<td class="col-md-2 text-center">'.$user['name'].'</td>';

                    $table .= '<td class="col-md-2 text-center">'.$user['profile'].'</td>';
					$table .= '<td class="col-md-1 text-center">'.$user['uptime'].'</td>';			
					$table .= '<td class="text-center">';
					
					$table .= anchor('hotspotController/remove/'.$user['.id'],'<button type="button" class="btn btn-danger btn-sm">
					<span class="glyphicon glyphicon-minus"></span> Remove</button>').' ';
					if ($user['disabled'] == 'false'){
						$table .= anchor('hotspotController/disable/'.$user['.id'],'<button type="button" class="btn btn-warning btn-sm">
						                                   <span class="glyphicon glyphicon-remove"></span> Disable</button>');
					}else{
						$table .= anchor('hotspotController/enable/'.$user['.id'],'<button type="button" class="btn btn-info btn-sm">
						                                    <span class="glyphicon glyphicon-ok"></span> Enable</button>');
					}
					$table .= '</td>';				
					$table .= '</tr>';
					$i++;
				}
				$table .= '</table>';
				$data['table'] = $table;
            }
            $data['container'] = 'hotspot/hotspot';	
			$data['link'] = array('link_tambah' => anchor('hotspotController/add', '<button type="button" class="btn btn-primary btn-sm">
			<span class="glyphicon glyphicon-plus"></span> Tambah</button>'));
			$this->load->view('template', $data);
		}
		
		

    }

    public function userAktif(){
        if($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

            $this->routerosapi->write('/ip/hotspot/active/getall');

            $users = $this->routerosapi->read();

            $this->routerosapi->disconnect();

            $total_results = count($users);
            if($total_results > 0){
                $data['total_results'] = $total_results;
                $table = '<table class="table table-bordered table-hover">';
                $table .= '<thead>';
                $table .= '<tr>';
				$table .= '<th class="text-center">No.</th>';
                $table .= '<th class="text-center">User</th>';
                $table .= '<th class="text-center">Address</th>'; 
                $table .= '<th class="text-center">Uptime</th>';
                $table .= '</tr>';
                $table .= '</thead>';
                $i = 1;
                foreach ($users as $user){	
					$table .= '<tr>';
					$table .= '<td class="col-md-1 text-center">'.$i.'.</td>';
					$table .= '<td class="col-md-1 text-center">'.$user['user'].'</td>';
                    $table .= '<td class="col-md-1 text-center">'.$user['address'].'</td>';
					$table .= '<td class="col-md-1 text-center">'.$user['uptime'].'</td>';			
				
					$table .= '</tr>';
					$i++;
				}
				$table .= '</table>';
				$data['table'] = $table;
            }
            $data['container'] = 'hotspot/hotspot';	
			$data['link'] = array('link_tambah' => anchor('hotspotController/add', '<button type="button" class="btn btn-primary btn-sm">
			<span class="glyphicon glyphicon-plus"></span> Tambah</button>'));
			$this->load->view('template1', $data);
		}
    }

    public function disable($id){
		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
			$this->routerosapi->write('/ip/hotspot/user/disable',false);
			$this->routerosapi->write('=.id='.$id);
			$hotspot_users = $this->routerosapi->read();
			$this->routerosapi->disconnect();	
			$this->session->set_flashdata('message','User Hotspot berhasil dinonaktifkan');
			redirect('hotspotController');
		}
    }
    
    public function enable($id){
		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
			$this->routerosapi->write('/ip/hotspot/user/enable',false);
			$this->routerosapi->write('=.id='.$id);
			$hotspot_users = $this->routerosapi->read();
			$this->routerosapi->disconnect();	
			$this->session->set_flashdata('message','User Hotspot berhasil diaktifkan');
			redirect('hotspotController');
		}
    }
    
    public function remove($id){
		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
			$this->routerosapi->write('/ip/hotspot/user/remove',false);
			$this->routerosapi->write('=.id='.$id);
			$hotspot_users = $this->routerosapi->read();
			$this->routerosapi->disconnect();	
			$this->session->set_flashdata('message','User Hotspot berhasil dihapus!');
			redirect('hotspotController');
		}
	}

	public function add(){
		$data['container'] = 'hotspot/hotspotForm.php';
		$data['form_action'] = site_url('hotspotController/add');				
				
		
		$this->form_validation->set_rules('server', 'Server', 'required');
		$this->form_validation->set_rules('profile', 'Profile', 'required');
		
		$this->form_validation->set_rules('name', 'Name', 'required');		
		$this->form_validation->set_rules('password', 'Password', 'required');	
				
		if ($this->form_validation->run() == TRUE){		

			$server = $this->input->post('server');	
			$profile = $this->input->post('profile');
			$name = $this->input->post('name');
			$password = $this->input->post('password'); 
			
			
			

			if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

				$this->routerosapi->write('/ip/hotspot/user/add', false);

				$this->routerosapi->write('=server='.$server, false);
				$this->routerosapi->write('=profile='.$profile, false);
				$this->routerosapi->write('=name='.$name, false);
				$this->routerosapi->write('=password='.$password);
			
				$hotspot_users = $this->routerosapi->read();
				$this->routerosapi->disconnect();	
				$this->session->set_flashdata('message','Data berhasil ditambahkan!');
				redirect('hotspotController');
			}
		}else{
			$data['default']['server'] = $this->input->post('server');
			$data['default']['profile'] = $this->input->post('profile');

		}
		$this->load->view('template', $data);				
	}

	public function update($id){
		$data['container'] = 'hotspot/hotspotForm';
		$data['form_action'] = site_url('hotspotController/update');		

		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

			$this->routerosapi->write("/ip/hotspot/user/print", false);			
			$this->routerosapi->write("=.proplist=.id", false);
			$this->routerosapi->write("=.proplist=server", false);
			$this->routerosapi->write("=.proplist=name", false);
			$this->routerosapi->write("=.proplist=address", false);
			$this->routerosapi->write("=.proplist=profile", false);		
			$this->routerosapi->write("=.proplist=uptime", false);
			$this->routerosapi->write("?.id=$id");
			
			$hotspot_user = $this->routerosapi->read();

			foreach ($hotspot_user as $row)
			{
				$server = $row['server'];
				$name = $row['name'];
				$address = $row['address'];
				$profile = $row['profile'];
				$uptime = $row['uptime'];
			}
			$this->routerosapi->disconnect();
			
			$this->session->set_userdata('id',$id);
			
			$data['default']['server'] = $server;
			$data['default']['name'] = $name;			
			$data['default']['address'] = $address;
			$data['default']['profile'] = $profile;
			$data['default']['uptime'] = $uptime;

		}
		$this->load->view('template', $data);
	}
	
	public function prosesUpdate(){
		$data['container'] = 'hotspot/hotspotForm';
		$data['form_action'] = site_url('hotspotController/prosesUpdate');	

		$this->form_validation->set_rules('server', 'server', 'required');
		$this->form_validation->set_rules('name', 'name', 'required');		
		$this->form_validation->set_rules('address', 'address', 'required');
		$this->form_validation->set_rules('profile', 'profile', 'required');		
		$this->form_validation->set_rules('uptime', 'uptime', 'required');	
		
		if ($this->form_validation->run() == TRUE)
		{	 
			$server = $this->input->post('server');
			$name = $this->input->post('name');
			$address = $this->input->post('address');
			$profile = $this->input->post('profile');
			$uptime = $this->input->post('uptime');
			
			if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
				$this->routerosapi->write('/ip/hotspot/user/set',false);		

				$this->routerosapi->write('=.id='.$this->session->userdata('id'), false);
				$this->routerosapi->write('=server='.$server, false);
				$this->routerosapi->write('=name='.$name, false);
				$this->routerosapi->write('=profile='.$profile, false);
				$this->routerosapi->write('=uptime='.$uptime, false);		
								
				$hotspot_users = $this->routerosapi->read();
				$this->routerosapi->disconnect();	
				$this->session->unset_userdata('id');
				$this->session->set_flashdata('message','Date User Hotspot berhasil dirubah');
				redirect('hotspotController');				
			}	
		}else{
			$data['default']['server'] = $this->input->post('server');
			$data['default']['name'] = $this->input->post('name');
			$data['default']['address'] = $this->input->post('address');
			$data['default']['profile'] = $this->input->post('profile');
			$data['default']['uptime'] = $this->input->post('uptime');
		}
		$this->load->view('template', $data);		
	}	
}
