<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  @Author : Mehul Patel
  @Desc   : Common Model For Insert, Update, Delete and Get all records
  @Input  :
  @Output :
  @Date   : 05/06/2017
 */

class Common_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->prefix = $this->db->dbprefix;
    }

    public function insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function insert_batch($table, $data) {
        return $this->db->insert_batch($table, $data);
    }

    public function update($table, $data, $where) {
        return $this->db->where($where)->update($table, $data);
    }
    public function update_batch($table, $data, $where_field) {
        return $this->db->update_batch($table,$data, $where_field);
    }
    
    public function delete($table, $where) {
        return $this->db->where($where)->delete($table);
    }

    /* Delete multiple id  by Mehul Patel */

    public function delete_where_in($table, $where, $field, $wherefield) {
        return $this->db->where($where)->where_in($field, $wherefield)->delete($table);
    }

//GET all type of data
    function get_records($table = '', $fields = '', $join_tables = '', $join_type = '', $match_and = '', $match_like = '', $num = '', $offset = '', $orderby = '', $sort = '', $group_by = '', $wherestring = '', $having = '', $where_in = '', $totalrow = '', $or_where = '', $where_not_in = '', $where_between = '') {
        if (!empty($fields)) {
            foreach ($fields as $coll => $value) {
                $this->db->select($value, false);
            }
        }

        $this->db->from($table);

        if (!empty($join_tables)) {
            foreach ($join_tables as $coll => $value) {
                $this->db->join($coll, $value, $join_type);
            }
        }
        if ($match_like != null)
            $this->db->or_like($match_like);
        if ($match_and != null)
            $this->db->where($match_and);

        if ($wherestring != '')
            $this->db->where($wherestring, NULL, FALSE);

        if ($where_between != '' && !empty($where_between)) {
            $this->db->where($where_between, NULL, FALSE);
        }
        if (!empty($where_in) && is_array($where_in)) {
            foreach ($where_in as $key => $value) {
                $this->db->where_in($key, $value);
            }
        }

        if (!empty($where_not_in)) {
            foreach ($where_not_in as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }

        if (!empty($or_where)) {
            foreach ($or_where as $key => $value) {
                $this->db->or_where($key, $value);
            }
        }

        if ($group_by != null)
            $this->db->group_by($group_by);
        if ($having != null)
            $this->db->having($having);
        if ($orderby != null && $sort != null)
            $this->db->order_by($orderby, $sort);



        if ($offset != null && $num != null)
            $this->db->limit($num, $offset);
        elseif ($num != null)
            $this->db->limit($num);
        $query_FC = $this->db->get();

        if (!empty($totalrow))
            return $query_FC->num_rows();
        else
            return $query_FC->result_array();
    }
    function get_userwise_shedule($date,$noOfPeople,$currentTime)
    {
       
         $query = $this->db->query("SELECT COUNT(*) as total FROM (SELECT u.hourly_ts_id  FROM res_user_shedule_time_slot as u 
left join res_hourly_time_slot as h on h.hourly_ts_id = u.hourly_ts_id where h.date = '".$date."' and concat(h.date,' ',h.start_time) >= '".$currentTime."' group by u.hourly_ts_id having (max(u.config_no_of_people) - sum(u.no_of_people)) >= ".$noOfPeople.") as total"); 
         return $query->result_array();
    }
    function get_datewise_shedule($date)
    {
       
         $query = $this->db->query("SELECT COUNT(*) as total_asign FROM (
                  SELECT u.hourly_ts_id  FROM res_user_shedule_time_slot as u 
                  left join res_hourly_time_slot as h on h.hourly_ts_id = u.hourly_ts_id where h.date = '".$date."'
                  group by u.hourly_ts_id having sum(u.no_of_people) = max(u.config_no_of_people)
                  ) as temp"); 
         return $query->result_array();
    }
    function hourly_available_shedule($weekly_ts_id,$noOfPeople,$currentTime)
    {
       
         $query = $this->db->query("SELECT COUNT(*) as total FROM (SELECT u.hourly_ts_id FROM res_user_shedule_time_slot as u left join res_hourly_time_slot as h on h.hourly_ts_id = u.hourly_ts_id where h.weekly_ts_id = ".$weekly_ts_id." and concat(h.date,' ',h.start_time) >= '".$currentTime."' group by u.hourly_ts_id having (max(u.config_no_of_people) - sum(u.no_of_people)) >= ".$noOfPeople.") as total"); 
         return $query->result_array();
    }
    function hourly_filled_shedule($weekly_ts_id)
    {
       
         $query = $this->db->query("SELECT COUNT(*) as total_asign FROM (SELECT u.weekly_ts_id  FROM res_user_shedule_time_slot as u left join res_hourly_time_slot as h on h.hourly_ts_id = u.hourly_ts_id where u.weekly_ts_id = ".$weekly_ts_id." group by u.hourly_ts_id having sum(u.no_of_people) = max(u.config_no_of_people)) as temp"); 
         return $query->result_array();
    }
    function get_records_array($params = array()) {

        if (array_key_exists("fields", $params)) {
            if (!empty($params['fields'])) {
                foreach ($params['fields'] as $coll => $value) {
                    $this->db->select($value, false);
                }
            }
        }

        if (array_key_exists("table", $params)) {
            $this->db->from($params['table']);
        }

        if (array_key_exists("join_tables", $params)) {
            if (!empty($params['join_tables'])) {
                foreach ($params['join_tables'] as $coll => $value) {
                    $this->db->join($coll, $value, $params['join_type']);
                }
            }
        }

        if (array_key_exists("match_like", $params)) {
            if ($params['match_like'] != null)
                $this->db->or_like($params['match_like']);
        }

        if (array_key_exists("match_and", $params)) {
            if ($params['match_and'] != null)
                $this->db->where($params['match_and']);
        }

        if (array_key_exists("wherestring", $params)) {
            if ($params['wherestring'] != '')
                $this->db->where($params['wherestring'], NULL, FALSE);
        }

        if (array_key_exists("where_between", $params)) {
            if ($params['where_between'] != '' && !empty($params['where_between'])) {
                $this->db->where($params['where_between'], NULL, FALSE);
            }
        }

        if (array_key_exists("where_in", $params)) {
            if (!empty($params['where_in'])) {

                foreach ($params['where_in'] as $key => $value) {

                    $this->db->where_in($key, $value);
                }
            }
        }


        if (array_key_exists("where_not_in", $params)) {
            if (!empty($params['where_not_in'])) {
                foreach ($params['where_not_in'] as $key => $value) {
                    $this->db->where_not_in($key, $value);
                }
            }
        }

        if (array_key_exists("or_where", $params)) {
            if (!empty($params['or_where'])) {
                foreach ($params['or_where'] as $key => $value) {
                    $this->db->or_where($key, $value);
                }
            }
        }

        if (array_key_exists("group_by", $params)) {
            if ($params['group_by'] != null)
                $this->db->group_by($params['group_by']);
        }

        if (array_key_exists("having", $params)) {
            if ($params['having'] != null)
                $this->db->having($params['having']);
        }

        if (array_key_exists("orderby", $params)) {
            if ($params['orderby'] != null && $params['sort'] != null)
                $this->db->order_by($params['orderby'], $params['sort']);
        }

        if (array_key_exists("offset", $params)) {
            if ($params['offset'] != null && $params['num'] != null)
                $this->db->limit($params['num'], $params['offset']);
            elseif ($params['num'] != null)
                $this->db->limit($params['num']);
        }

        $query_FC = $this->db->get();
//echo $this->db->last_query();exit;
        if (array_key_exists("totalrow", $params)) {
            if (!empty($params['totalrow'])) {
                return $query_FC->num_rows();
            }
        } else {
            return $query_FC->result_array();
        }
    }

//Function For Get Config
    public function get_config() {
        return $this->db->get('config');
    }

    /* Start added by sanket om 27/01/2016 */

    public function pagination_html($config) {
        $this->load->library('pagination');


// $config["uri_segment"] = $config["uri_segment"];
        $config["uri_segment"] = 3;

        $config["uri_segment"] = 3;

        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }

    /* Start added by Mehul Patel om 05/06/2017 */

    public function common_pagination($config, $page_url) {
        $config["uri_segment"] = $config["uri_segment"];
        $choice = $config["total_rows"] / $config["per_page"];
        $config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01 pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="' . $page_url . '">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['first_link'] = '&lt;&lt;';
        $config['last_link'] = '&gt;&gt;';

        $this->pagination->cur_page = 4;

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    public function customQuery($sql) {
        $result = $this->db->query($sql, false);
        return $result->result_array();
    }

    function upload_image($uploadFile = '', $bigImgPath = '', $smallImgPath = '', $thumb = '', $existImage = '') {

        if (!empty($existImage)) {
            $path = $bigImgPath . $existImage;
            $path_thumb = $smallImgPath . $existImage;
            @unlink($path);
            @unlink($path_thumb);
        }
        $upload_name = $uploadFile;
        $config['upload_path'] = $bigImgPath; /* NB! crea          te this dir! */
//$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg|csv|doc|docx|txt|pdf|xls|mov|mp4|PNG';
        $config['allowed_types'] = '*';

        $random = substr(md5(rand()), 0, 7);
        $config['file_name'] = $random . "-" . (strtolower($_FILES[$uploadFile]['name']));
        $config['overwrite'] = false;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload($upload_name))
            $data = $this->upload->data();
        else {
            echo $this->upload->display_errors();
            exit;
        }
//Upload thumb image
        $sourcePath = $data['full_path'];
        $thumbPath = $smallImgPath;
        $fileName = $data['file_name'];

        list($width, $height, $type, $attr) = getimagesize($bigImgPath . $fileName);

        if (!empty($thumb) && $thumb == 'thumb') {
            if (!file_exists($smallImgPath)) {
                mkdir($bigImgPath, 0777);
            }

            $basename = explode('.', $_FILES[$uploadFile]['name']);
            $filename = $basename[0];
//for create small image
            if ($data['file_type'] == 'image/bmp' || $basename[1] == 'bmp') {
                $sourceImgBig = base_url() . $bigImgPath . $fileName;
                copy($sourceImgBig, $smallImgPath . $filename . ".jpeg");
                $imgurl = base_url() . $smallImgPath . $filename . ".jpeg";
                $width = 150;
                $this->make_thumb($imgurl, $smallImgPath . $fileName, $width);
                @unlink($smallImgPath . $filename . ".jpeg");
            } else {
                $filename = $this->upload_small_image($sourcePath, $thumbPath, $fileName);
            }


            return $fileName;
        }
    }

    //Send mail using smtp
    function sendEmail($to = '', $subject = '', $message = '', $from = '', $cc = '', $bcc = '', $attach = '') {
         $config = Array(
            "protocol" => "smtp",
            "smtp_host" => "ssl://smtp.gmail.com",
			//"smtp_host" => "10.1.5.19",
			"smtp_port" => 465,
			//"smtp_port" => 25,
            //"smtp_timeout" => "30",
            //"smtp_user" => "reservationsystem.test@gmail.com",
            //"smtp_pass" => "Gmail@123",
            "smtp_user" => "cmswtest101@gmail.com",
            "smtp_pass" => "Test@123",
            "charset" => "utf-8",
            "mailtype" => "html",
            "newline" => "\r\n",
        );

        $this->load->library('email', $config);

        //$this->email->initialize($config);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->from($from, SITENAME);
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->bcc($bcc);
        if (isset($attach) && $attach != "") {
            $this->email->attach($attach); // attach file /path/to/file1.png
        }
        if ($this->email->send()) {
            $this->email->clear(TRUE);
            return 1;
        } else {           
            return 0;
        }
        $this->email->clear(TRUE);
    }

    public function get_lang() {
        $this->load->helper('cookie');
        $selectedLang = get_cookie('languageSet');      
        if ($selectedLang) {
            return $selectedLang;
        } else {
            $query = $this->db->select('value')->where('config_key', 'language')->get('config');
            if ($query->num_rows() > 0) {
                $row = $query->row();
                return $row->value;
            }
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Get User Role
      @Input  :
      @Output :
      @Date   : 01/02/2016
     */

    public function getRoles() {
        $this->db->select('*')->from(ROLE_MASTER);
        $this->db->where('`role_id` NOT IN (SELECT `role_id` FROM ' . $this->db->dbprefix(AAUTH_PERMS_TO_ROLE) . ')', NULL, FALSE);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    /*
      @Author : Mehul Patel
      @Desc   : checkRoleAssigne
      @Input  : $id
      @Output :
      @Date   : 01/02/2016
     */

    public function checkRoleAssigne($id = null) {
        if ($id) {
            $this->db->select('lg.role_type,lg.status as userStatus,rm.status as roleStatus');
            $this->db->from(USER . ' AS lg');
            $this->db->join(ROLE_MASTER . ' AS rm', 'lg.role_type = rm.role_id', 'LEFT');
            $this->db->where('rm.role_id', $id);
            $this->db->where('lg.is_delete = 0');
            $query = $this->db->get();
            $result = $query->result_array();           
            return $result;
        }
    }

}
