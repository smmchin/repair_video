<?php
    class GetVideo_Model extends CI_Model 
    {
        function returnVideo($case,$video)
        {
            $sql="select * from video  where case_id='$case' AND video_id='$video'";
            $query=$this->db->query($sql);

            return $query->result();
        }
    }

?>