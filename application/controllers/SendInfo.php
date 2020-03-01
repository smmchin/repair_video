<?php
    class SendInfo extends CI_Controller 
    {
        public function __construct()
        {
            //call CodeIgniter's default Constructor
            parent::__construct();
            
            $this->load->helper('url');
            $this->load->helper('form');

            $this->load->library('session');


            //load database libray manually
            $this->load->database();
            
            //load Model
            $this->load->model('SendInfo_Model');
        }
        
        public function getinfo()
        {
            //load form
            $this->load->view('getinfoform');        

            if($this->input->post('submit'))
            {
                $c=$this->input->post('casenumber');
                $k=$this->input->post('keyword');
                $b=$this->input->post('branch');

                //check if casenumber tally with branch
                $data = array();
                $data=$this->SendInfo_Model->checkCaseBranch($c,$b);

                if(count($data) == 0)
                {
                    echo "Case number and branch code does not tally.";
                }
                else
                {
                    //echo "Match found.";
                    redirect("/SendInfo/addvideo/$b/$c", 'refresh');
                }

            }
        }

        public function addvideo($b, $c){

            $this->load->view('addvideo');

            //TODO: do another check for correct branch before upload.

            if($this->input->post('submit'))
            {
                if (isset($_FILES['video']['name']) && $_FILES['video']['name'] != '') {
                    unset($config);
                    $date = date("ymd");
                    $configVideo['upload_path'] = './video/'.$b.'/';
                    $configVideo['max_size'] = '10240';
                    $configVideo['allowed_types'] = '*';
                    $configVideo['overwrite'] = FALSE;
                    $configVideo['remove_spaces'] = TRUE;
                    $video_name = $date.$_FILES['video']['name'];
                    $configVideo['file_name'] = $video_name;
        
                    $this->load->library('upload', $configVideo);
                    $this->upload->initialize($configVideo);   
                    
                    //TODO: Add video to DB.
                    
    
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