<?php
    class Video extends CI_Controller 
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
            $this->load->model('Video_Model');
        }

        public function index(){

            $this->load->view('chooseoptions'); 

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
                $data=$this->Video_Model->checkCaseBranch($c,$b);

                if(count($data) == 0)
                {
                    echo "<br><h1>Case number and branch code does not tally.</h1></br>";
                }
                else
                {
                    //echo "Match found.";
                    redirect("/Video/addvideowithapi/$b/$c", 'refresh');
                }

            }
        }

        public function retrievevideo(){
            //load form
            $this->load->view('retrievevideo');

            if($this->input->post('submit'))
            {
                $v=$this->input->post('videonumber');

                //retrieve via API
                $json=file_get_contents(base_url()."api/Video/$v/get");

                if(!empty($json)){
                    $r=json_decode($json, true);
                    //var_dump($r["video_url"]);
                    $data['video_url'] = base_url().$r["video_url"];
                    $this->load->view('getvideo', $data);
    
                }else{
                    echo "<br><h1> Video Not Found. Try again.</h1><br>";
                }  
            }

        }


        /*public function getvideo(){
           
            $json=file_get_contents(base_url().'api/Video/6/get');

            if(!empty($json)){
                $r=json_decode($json, true);
                //var_dump($r["video_url"]);
                $data['video_url'] = base_url().$r["video_url"];
                $this->load->view('getvideo', $data);

            }else{
                echo "Video Not Found.";
            }            

        }*/


        public function addvideowithapi($b, $c){

            $this->load->view('addvideo');

            if($this->input->post('submit'))
            {
                if (isset($_FILES['video']['name']) && $_FILES['video']['name'] != '') {

                    $ufile = $this->input->post('video');

                    $filePath = $_FILES['video']['tmp_name'];
                    $type=$_FILES['video']['type'];
                    $fileName = $_FILES['video']['name'];  

                    $data = array('video' => curl_file_create($filePath, $type, $fileName));

                    $curlFile = curl_file_create($filePath, $type, $fileName);

                    $data =  array('case'=> $c, 'branch'=> $b, 'file' => $curlFile);

                    $ch = curl_init();                    
                    curl_setopt($ch, CURLOPT_URL, "https://repair.projects-xyz.com/api/Video/upload");
                    //curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: multipart/form-data'));
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    curl_close($ch);   
                    
                    if (strpos($response, 'error') !== true) {
                        redirect("/Video", 'refresh');
                    }

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
                    $configVideo['allowed_types'] = 'avi|mp4';
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
                        //TODO: Add video to DB. Insert path. 
                        $file_location = 'video/'.$b.'/'.$video_name;
                        $this->Video_Model->addVideo($c,$file_location);
                        //echo "Successfully Uploaded";
                        redirect("/Video", 'refresh');
                    }
                   
                }
            }

        }
    }
?>