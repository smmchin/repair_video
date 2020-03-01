<?php
    class Video_Model extends CI_Model 
    {
        function checkCaseBranch($case,$branch)
        {
            $sql="select * from cases where case_id='$case' AND branch_id='$branch'";
            $query=$this->db->query($sql);

            return $query->result();
        }
    }

?>