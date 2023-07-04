<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Controller extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(['form_validation']);
		$this->load->model('Users_model');

	}


	public function index ()
    {

        if(isset($_SESSION['user'])){
            redirect(base_url('index.php/dashboard'));
        }

        if (isset($_POST['login_btn'])) {
            $email= $this->input->post('user_email');
            $pw= $this->input->post('user_password');

            $user_data=$this->Users_model->authenticate($email,$pw);

            if($user_data!==0){

                $user_info = [
                    'user_id'=>$user_data[0]->id,
                    'fullname'=>$user_data[0]->fullname,
                ];

                $this->session->set_userdata('user',$user_info);
                redirect('dashboard');

            }else{

                $this->session->set_flashdata('msg_login','Invalid Password. Please try again.');
            }
    
        }

        $this->load->view('backend/pages/login');
    }


	public function dashboard()
	{
		if(!isset($_SESSION['user'])){
			$this->session->set_flashdata('msg_login', 'Please Login');
			redirect(base_url('index.php/admin'));
		}
		$this->load->view('backend/include/header');
		$this->load->view('backend/include/nav');
		$this->load->view('backend/pages/dashboard');
		$this->load->view('backend/include/footer');
		
	}

	
	public function logout()
	{
		$this->session->unset_userdata('user');
		redirect(base_url('index.php/admin'));
	}

	
    public function addresident(){
        
        if(!isset($_SESSION['user'])){
            $this->session->set_flashdata('msg_login','You are not logged in. Please Login First');
            redirect(base_url('index.php/admin'));
        }


        $this->form_validation->set_rules('firstname','First Name','trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('lastname','Last Name','trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('birth_date','Birth Date','trim|required');
        $this->form_validation->set_error_delimiters('<p style="color:red;">','<p>');

        if($this->form_validation->run()==FALSE){

            $this->load->view('backend/include/header');
            $this->load->view('backend/include/nav');
            $this->load->view('backend/pages/addresident');
            $this->load->view('backend/include/footer');

        }else{

            $resident_data = [
                'first_name'=>$this->input->post('firstname',TRUE),
                'last_name'=>$this->input->post('lastname',TRUE),
                'birth_date'=>$this->input->post('birth_date',TRUE),
            ];


            $insert = $this->db->insert('resident',$resident_data);

            $insert_id = $this->db->insert_id();

            if( is_int($insert_id) ){
                redirect(base_url('index.php/dashboard/view-resident'));
            }


        }
        


    }

	public function viewresident(){

        if(!isset($_SESSION['user'])){
            $this->session->set_flashdata('msg_login','You are not logged in. Please Login First');
            redirect(base_url('index.php/admin'));
        }


        $resident_list = $this->db->get('resident')->result();

        $data = ['resident_list'=>$resident_list];

        $this->load->view('backend/include/header');
        $this->load->view('backend/include/nav');
        $this->load->view('backend/pages/viewresident',$data);
        $this->load->view('backend/include/footer');
    }

  /*  public function delete($resident_id) {
        $this->load->model('Users_model');
        if ($this->Users_model->delete_user($resident_id)) {
            redirect(base_url('index.php/dashboard/view-resident')); // Redirect to user list page or any other page
        } else {
            echo 'Failed to delete the user.';
        }
    }*/

    public function deleteresident($id){
        $this->db->db_debug = TRUE;
        $this->db->where('resident_id', $id);
        $this->db->delete('resident');
        redirect(base_url('index.php/dashboard/view-resident'));
    }

  
    public function updateresident($resident_id){
        if(!isset($_SESSION['user'])){
            $this->session->set_flashdata('msg_login','You are not logged in. Please Login First');
            redirect(base_url('index.php/admin'));
        }
    
        $this->form_validation->set_rules('firstname','First Name','trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('lastname','Last Name','trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('birth_date','Birth Date','trim|required');
        $this->form_validation->set_error_delimiters('<p style="color:red;">','<p>');
    
        if($this->form_validation->run()==FALSE){
            $data = [
                'resident_id' => $resident_id,
                'resident' => $this->db->get_where('resident', array('resident_id' => $resident_id))->row()
            ];
            $this->load->view('backend/include/header');
            $this->load->view('backend/include/nav');
            $this->load->view('backend/pages/updateresident', $data);
            $this->load->view('backend/include/footer');
        } else {
            $resident_data = [
                'first_name'=>$this->input->post('firstname',TRUE),
                'last_name'=>$this->input->post('lastname',TRUE),
                'birth_date'=>$this->input->post('birth_date',TRUE),
            ];
    
            $this->db->where('resident_id', $resident_id);
            $update = $this->db->update('resident', $resident_data);
    
            if($update){
                redirect(base_url('index.php/dashboard/view-resident'));
            } else {
                // Handle update error
            }
        }
    }

    
	


	


}
