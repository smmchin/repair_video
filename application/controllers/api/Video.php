<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Video extends REST_Controller {
    
	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();

       //load Model
       $this->load->model('Video_Model');
    }
       
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
	public function index_get($id = 0)
	{
        if(!empty($id)){
            $data = $this->db->get_where("video", ['video_id' => $id])->row_array();
        }else{
            $data = $this->db->get("video")->result();
        }
     
        $r=$this->response($data, REST_Controller::HTTP_OK);

        return $r;
    }
    
     /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_post()
    {
        $input = $this->input->post();
        $this->db->insert('video',$input);
     
        $this->response(['Video created successfully.'], REST_Controller::HTTP_OK);
    }

    public function upload_post()
    {
        $input = $this->input->post();
        $this->load->helper(array('form', 'url'));

        $b=$input['branch'];
        $c=$input['case'];
    
        $config = array(
            'upload_path' => "./video/$b/",
            'allowed_types' => 'avi|mp4',
            'max_size' => "2048000",
            'max_height' => "768",
            'max_width' => "1024"
        );
    
        $this->load->library('upload',$config);
    
        if($this->upload->do_upload('file'))
        {            
            $data = array('upload_data' => $this->upload->data());
            
            $file_location="video/$b/".$data['upload_data']['file_name'];
            $this->Video_Model->addVideo($c,$file_location);

            $this->set_response($data, REST_Controller::HTTP_CREATED);
        }
        else
        {
            $error = array('error' => $this->upload->display_errors());
            $this->response($error, REST_Controller::HTTP_BAD_REQUEST);            
        }
    
    }
}