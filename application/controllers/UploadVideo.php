<?php
    class UploadVideo extends CI_Controller 
    {
        public function __construct()
        {
            //call CodeIgniter's default Constructor
            parent::__construct();
            
            $this->load->helper('url');
            $this->load->helper('form');

            //load database libray manually
            //$this->load->database();
            
            //load Model
            //$this->load->model('SendInfo_Model');
        }

        public function addvideo(){

            $this->load->view('addvideo');

            if($this->input->post('submit'))
            {
                if (isset($_FILES['video']['name']) && $_FILES['video']['name'] != '') {
                    unset($config);
                    $date = date("ymd");
                    $configVideo['upload_path'] = './video/';
                    $configVideo['max_size'] = '10240';
                    $configVideo['allowed_types'] = '*';
                    $configVideo['overwrite'] = FALSE;
                    $configVideo['remove_spaces'] = TRUE;
                    $video_name = $date.$_FILES['video']['name'];
                    $configVideo['file_name'] = $video_name;
        
                    $this->load->library('upload', $configVideo);
                    $this->upload->initialize($configVideo);                    
    
                    if (!$this->upload->do_upload('video')) {
                        echo $this->upload->display_errors();
                    } else {
                        $videoDetails = $this->upload->data();
                        echo "Successfully Uploaded";
                    }
                   
                }
            }

        }
    }
?>