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
}