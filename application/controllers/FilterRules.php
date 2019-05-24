<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FilterRules extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('routerosapi');
    }

    var $hostname = '192.168.40.1';
    var $username = 'admin';
    var $password = '';

    public function index(){
        if($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

            $this->routerosapi->write('/ip/firewall/filter/getall');

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
                $table .= '<th class="text-center">Reject With</th>';
                $table .= '<th class="text-center">Dst Address List</th>';
				$table .= '<th class="text-center">Disabled</th>';
				$table .= '<th class="text-center">#</th>';
                $table .= '</tr>';
                $table .= '</thead>';
                $i = 1;
                foreach ($users as $user){	
					$table .= '<tr>';
					$table .= '<td class="col-md-1 text-center">'.$i.'.</td>';
					$table .= '<td class="col-md-2 text-center">'.$user['chain'].'</td>';
					$table .= '<td class="col-md-1 text-center">'.$user['action'].'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$user['reject-with'].'</td>';
                    $table .= '<td class="col-md-2 text-center">'.$user['dst-address-list'].'</td>';
					$table .= '<td class="col-md-1 text-center">'.$user['disabled'].'</td>';
						
					$table .= '<td>';
					$table .= anchor('filterRules/update/'.$user['.id'],'<button type="button" class="btn btn-success btn-sm">
					<span class="glyphicon glyphicon-edit"></span> Update</button>').' ';
					$table .= anchor('filterRules/remove/'.$user['.id'],'<button type="button" class="btn btn-danger btn-sm">
					<span class="glyphicon glyphicon-minus"></span> Remove</button>').' ';
					if ($user['disabled'] == 'false'){
						$table .= anchor('filterRules/disable/'.$user['.id'],'<button type="button" class="btn btn-warning btn-sm">
						                                   <span class="glyphicon glyphicon-remove"></span> Disable</button>');
					}else{
						$table .= anchor('filterRules/enable/'.$user['.id'],'<button type="button" class="btn btn-info btn-sm">
						                                    <span class="glyphicon glyphicon-ok"></span> Enable</button>');
					}
					$table .= '</td>';				
					$table .= '</tr>';
					$i++;
				}
				$table .= '</table>';
				$data['table'] = $table;
            }
            $data['container'] = 'filterRules/filterRules';	
			$data['link'] = array('link_tambah' => anchor('filterRules/add', '<button type="button" class="btn btn-primary btn-sm">
			<span class="glyphicon glyphicon-plus"></span> Tambah</button>'));
			$this->load->view('template', $data);
        }
    }

    public function coba(){
        if($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

            $this->routerosapi->write('/ip/firewall/filter/getall');

			$hehe = $this->routerosapi->read();
			
			$this->routerosapi->disconnect();

			$total_result = count($hehe);
            if ($total_result > 0) {
				$table = '<select class="form-control">';
                $i = 1;
                foreach ($hehe as $heh) {
                    $table .= '<option value='.$heh['dst-address-list'].'>'.$heh['dst-address-list'].'</option>';
					$i++;
				}
				$table .= '</select>';
				$data['table'] = $table;
            }
			$data['container'] = 'filterRules/filterRules';
            $this->load->view('template', $data);
        }
        
	}

    public function disable($id){
		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
			$this->routerosapi->write('/ip/firewall/filter/disable',false);
			$this->routerosapi->write('=.id='.$id);
			$hotspot_users = $this->routerosapi->read();
			$this->routerosapi->disconnect();	
			$this->session->set_flashdata('message','Data filter rules berhasil dinonaktifkan');
			redirect('filterRules');
		}
    }
    
    public function enable($id){
		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
			$this->routerosapi->write('/ip/firewall/filter/enable',false);
			$this->routerosapi->write('=.id='.$id);
			$hotspot_users = $this->routerosapi->read();
			$this->routerosapi->disconnect();	
			$this->session->set_flashdata('message','Data filter rules berhasil diaktifkan');
			redirect('filterRules');
		}
    }
    
    public function remove($id){
		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
			$this->routerosapi->write('/ip/firewall/filter/remove',false);
			$this->routerosapi->write('=.id='.$id);
			$hotspot_users = $this->routerosapi->read();
			$this->routerosapi->disconnect();	
			$this->session->set_flashdata('message','Data filter rules berhasil dihapus!');
			redirect('filterRules');
		}
	}

	public function add(){
		$data['container'] = 'filterRules/filterRulesForm.php';
		$data['form_action'] = site_url('filterRules/add');				
	
		$this->form_validation->set_rules('dstAddressList', 'DstAddressList', 'required');	
				
		if ($this->form_validation->run() == TRUE){		

			$chain = 'forward';
			$dstAddressList = $this->input->post('dstAddressList');
			$action = 'reject';
			$rejectWith = 'icmp-network-unreachable';
			$disabled = 'no';
			
			if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

				$this->routerosapi->write('/ip/firewall/filter/add', false);

				$this->routerosapi->write('=chain='.$chain, false);
				$this->routerosapi->write('=dst-address-list='.$dstAddressList, false);
				$this->routerosapi->write('=action='.$action, false);
				$this->routerosapi->write('=reject-with='.$rejectWith, false);
				$this->routerosapi->write('=disabled='.$disabled);
			
				$hotspot_users = $this->routerosapi->read();

				$this->routerosapi->disconnect();	
				$this->session->set_flashdata('message','Data berhasil ditambahkan!');
				redirect('filterRules');
			}
		}else{
			$data['default']['dstAddressList'] = $this->input->post('dstAddressList');

		}
		$this->load->view('template', $data);				
	}

	public function update($id){
		$data['container'] = 'filterRules/filterRulesForm';
		$data['form_action'] = site_url('filterRules/prosesUpdate');		

		if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){

			$this->routerosapi->write("/ip/firewall/filter/print", false);	

			$this->routerosapi->write("=.proplist=.id", false);
			$this->routerosapi->write("=.proplist=chain", false);
			$this->routerosapi->write("=.proplist=dst-address-list", false);
			$this->routerosapi->write("=.proplist=action", false);
			$this->routerosapi->write("=.proplist=reject-with", false);		
			$this->routerosapi->write("=.proplist=disabled", false);
			$this->routerosapi->write("?.id=$id");
			
			$hotspot_user = $this->routerosapi->read();

			foreach ($hotspot_user as $row)
			{
				$chain = $row['chain'];
				$dstAddressList = $row['dst-address-list'];
				$action = $row['action'];
				$rejectWith = $row['reject-with'];
				$disabled = $row['disabled'];
			}
			$this->routerosapi->disconnect();
			
			$this->session->set_userdata('id',$id);
			
			$data['default']['chain'] = $chain;
			$data['default']['dst-address-list'] = $dstAddressList;			
			$data['default']['action'] = $action;
			$data['default']['addressList'] = $addressList;
			$data['default']['content'] = $content;

		}
		$this->load->view('template', $data);
	}
	
	public function prosesUpdate(){
		$data['container'] = 'filterRules/filterRulesForm';
		$data['form_action'] = site_url('filterRules/prosesUpdate');	

		$this->form_validation->set_rules('dstAddressList', 'SrcAddress', 'required');
		
		if ($this->form_validation->run() == TRUE)
		{
			$chain = 'forward';
			$dstAddressList = $this->input->post('dstAddressList');
			$action = 'reject';
			$rejectWith = 'icmp-network-unreachable';
			$disabled = 'no';
			
			if ($this->routerosapi->connect($this->hostname, $this->username, $this->password)){
				$this->routerosapi->write('/ip/firewall/filter/set',false);		

				$this->routerosapi->write('=.id='.$this->session->userdata('id'), false);

				$this->routerosapi->write('=chain='.$chain, false);
				$this->routerosapi->write('=dst-address-list='.$dstAddressList, false);
				$this->routerosapi->write('=action='.$action, false);
				$this->routerosapi->write('=reject-with='.$rejectWith, false);
				$this->routerosapi->write('=disabled='.$disabled);			
								
				$hotspot_users = $this->routerosapi->read();
				$this->routerosapi->disconnect();	
				$this->session->unset_userdata('id');
				$this->session->set_flashdata('message','Data filter rules berhasil dirubah');
				redirect('filterRules');				
			}	
		}else{
			$data['default']['dstAddressList'] = $this->input->post('dstAddressList');
		}
		$this->load->view('template', $data);		
	}	
}
