<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BlokSitus extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('routerosapi');
    }

    var $hostname = '192.168.40.1';
    var $username = 'admin';
    var $password = '';

    public function index(){
        if($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

            $this->routerosapi->write('/ip/firewall/mangle/getall');

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

    public function disable($id){
		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
			$this->routerosapi->write('/ip/firewall/mangle/disable',false);
			$this->routerosapi->write('=.id='.$id);
			$hotspot_users = $this->routerosapi->read();
			$this->routerosapi->disconnect();	
			$this->session->set_flashdata('message','Data mangle berhasil dinonaktifkan');
			redirect('blokSitus');
		}
    }
    
    public function enable($id){
		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
			$this->routerosapi->write('/ip/firewall/mangle/enable',false);
			$this->routerosapi->write('=.id='.$id);
			$hotspot_users = $this->routerosapi->read();
			$this->routerosapi->disconnect();	
			$this->session->set_flashdata('message','Data mangle berhasil diaktifkan');
			redirect('blokSitus');
		}
    }
    
    public function remove($id){
		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
			$this->routerosapi->write('/ip/firewall/mangle/remove',false);
			$this->routerosapi->write('=.id='.$id);
			$hotspot_users = $this->routerosapi->read();
			$this->routerosapi->disconnect();	
			$this->session->set_flashdata('message','Data mangle berhasil dihapus!');
			redirect('blokSitus');
		}
	}

	public function addSatu(){
		$data['container'] = 'mangle/mangleForm.php';
		$data['form_action'] = site_url('blokSitus/addSatu');

		$action = 'add-dst-to-address-list';
		$chain = 'forward';
		$srcAddress = $this->input->post('srcAddress');
		$content = $this->input->post('content');
		$addressList = $this->input->post('addressList');

		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
			$this->routerosapi->write('/ip/firewall/mangle/add', false);
		
			$this->routerosapi->write('=action='.$action, false);
			$this->routerosapi->write('=chain='.$chain, false);
			$this->routerosapi->write('=src-address='.$srcAddress, false);
			$this->routerosapi->write('=content='.$content, false);
			$this->routerosapi->write('=address-list='.$addressList, false);

			$hotspot_users = $this->routerosapi->read();
			$this->routerosapi->disconnect();
			$this->session->set_flashdata('message','Data berhasil ditambahkan!');
			redirect('blokSitus');

			
		}
		$this->load->view('template', $data);
		
	}

	public function add(){
		$data['container'] = 'mangle/mangleForm.php';
		$data['form_action'] = site_url('blokSitus/add');				
				
		$this->form_validation->set_rules('srcAddress', 'SrcAddress', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');		
		$this->form_validation->set_rules('addressList', 'AddressList', 'required');	
				
		if ($this->form_validation->run() == TRUE){		

			$chain = 'forward';	 
			$srcAddress = $this->input->post('srcAddress');
			$content = $this->input->post('content');
			$action = 'add-dst-to-address-list';
			$addressList = $this->input->post('addressList');
			
			

			if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

				$this->routerosapi->write('/ip/firewall/mangle/add', false);

				$this->routerosapi->write('=chain='.$chain, false);
				$this->routerosapi->write('=src-address='.$srcAddress, false);
				$this->routerosapi->write('=content='.$content, false);
				$this->routerosapi->write('=action='.$action, false);
				$this->routerosapi->write('=address-list='.$addressList);
			
				$hotspot_users = $this->routerosapi->read();
				$this->routerosapi->disconnect();	
				$this->session->set_flashdata('message','Data berhasil ditambahkan!');
				redirect('blokSitus');
			}
		}else{
			$data['default']['srcAddress'] = $this->input->post('srcAddress');
			$data['default']['content'] = $this->input->post('content');
			$data['default']['addressList'] = $this->input->post('addressList');

		}
		$this->load->view('template', $data);				
	}

	public function update($id){
		$data['container'] = 'mangle/mangleForm';
		$data['form_action'] = site_url('blokSitus/prosesUpdate');		

		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

			$this->routerosapi->write("/ip/firewall/mangle/print", false);			
			$this->routerosapi->write("=.proplist=.id", false);
			$this->routerosapi->write("=.proplist=chain", false);
			$this->routerosapi->write("=.proplist=action", false);
			$this->routerosapi->write("=.proplist=src-address", false);
			$this->routerosapi->write("=.proplist=address-list", false);		
			$this->routerosapi->write("=.proplist=content", false);
			$this->routerosapi->write("?.id=$id");
			
			$hotspot_user = $this->routerosapi->read();

			foreach ($hotspot_user as $row)
			{
				$chain = $row['chain'];
				$action = $row['action'];
				$srcAddress = $row['src-address'];
				$content = $row['content'];
				$addressList = $row['address-list'];
			}
			$this->routerosapi->disconnect();
			
			$this->session->set_userdata('id',$id);
			
			$data['default']['chain'] = $chain;
			$data['default']['action'] = $action;			
			$data['default']['srcAddress'] = $srcAddress;
			$data['default']['addressList'] = $addressList;
			$data['default']['content'] = $content;

		}
		$this->load->view('template', $data);
	}
	
	public function prosesUpdate(){
		$data['container'] = 'mangle/mangleForm';
		$data['form_action'] = site_url('blokSitus/prosesUpdate');	

		$this->form_validation->set_rules('srcAddress', 'SrcAddress', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');		
		$this->form_validation->set_rules('addressList', 'AddressList', 'required');	
		
		if ($this->form_validation->run() == TRUE)
		{
			$chain = 'forward';	 
			$srcAddress = $this->input->post('srcAddress');
			$content = $this->input->post('content');
			$action = 'add-dst-to-address-list';
			$addressList = $this->input->post('addressList');
			
			if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
				$this->routerosapi->write('/ip/firewall/mangle/set',false);		

				$this->routerosapi->write('=.id='.$this->session->userdata('id'), false);
				$this->routerosapi->write('=chain='.$chain, false);
				$this->routerosapi->write('=src-address='.$srcAddress, false);
				$this->routerosapi->write('=content='.$content, false);
				$this->routerosapi->write('=action='.$action, false);
				$this->routerosapi->write('=address-list='.$addressList);			
								
				$hotspot_users = $this->routerosapi->read();
				$this->routerosapi->disconnect();	
				$this->session->unset_userdata('id');
				$this->session->set_flashdata('message','Data mangle berhasil dirubah');
				redirect('blokSitus');				
			}	
		}else{
			$data['default']['srcAddress'] = $this->input->post('srcAddress');
			$data['default']['content'] = $this->input->post('content');
			$data['default']['addressList'] = $this->input->post('addressList');
		}
		$this->load->view('template', $data);		
	}	
}
