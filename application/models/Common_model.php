<?php
class Common_model extends MY_Model{
		
	public function __construct()
	{
		parent::__construct();
		
	}
	
	//	Function for registration send email
	public function singUpMail($data)
	{
		$options = array(
						'table'  => $this->tables['email_templates'],
						'select' => '*',
						'where'  => array('slug' => $data['slug'],'temp_type' => $data['temp_type']),
						'single' => true
					);
		$tempData = $this->customGet($options);
		
		$subject  = $tempData->subject;
		
		$msgbody = $tempData->body;
		$msgbody = str_ireplace("{site_link}",base_url(),$msgbody);
		$msgbody = str_ireplace("{userLink}",$data['userLink'],$msgbody);
		
		$sendData = array(
						'toEmail' => $data['toEmail'],
						'subject' => $subject,
						'body'    => $msgbody,
						'cc'      => $data['cc']
					);
		return $this->customMail($sendData);
	}
	
	//	Function for custom query where
	public function customQueryWhere($table,$where)
	{
		$result = $this->db->query('select * from '.$table.' '.$where)->result();
		return $result;
	}
	
	public function RandomNumber()
	{
		$length = 4;
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
	
	//	Function for custom query where with one join
	public function customQueryWhereOneJoin($table,$where,$table1,$value1)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($where);
		$this->db->join($table1,$value1);
		$query = $this->db->get();
		
		return $query->row();
	}

	//	Function for custom query where with one join
	public function queryWhereMultipleJoinRow($table = false, $where_array = false, $join_array = false)
	{
		$this->db->select('*');
		$this->db->from($table);

		//$this->db->where($where_array);

		if(!empty($where_array))
		{
			foreach($where_array as $key=>$value){
				$this->db->join($key,$value);
			}	
		}

		if(!empty($join_array))
		{
			foreach($join_array as $key=>$value){
				$this->db->join($key,$value);
			}	
		}

		$query = $this->db->get();
		
		return $query->row();
	}

	public function queryWhereMultipleJoinResult1($table = false, $where_array = false, $join_array = false)
	{
		$this->db->select('*');
		$this->db->from($table);

		//$this->db->where($where_array);

		if(!empty($where_array))
		{
			foreach($where_array as $key=>$value){
				$this->db->join($key,$value);
			}	
		}

		if(!empty($join_array))
		{
			foreach($join_array as $key=>$value){
				$this->db->join($key,$value);
			}	
		}

		$query = $this->db->get();
		
		return $query->result();
	}
	
	//	Function for custom query where with one join
	public function queryWhereMultipleJoinResult($table = false, $where_array = false, $join_array = false)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($where_array);

		if(!empty($join_array))
		{
			foreach($join_array as $key=>$value){
				$this->db->join($key,$value);
			}	
		}

		$query = $this->db->get();
		
		return $query->result();
	}

	//	Function for custom query where with one join
	public function queryWhereMultipleLeftJoinResult($select = '*', $table = false, $where_array = false, $join_array = false)
	{
		$this->db->select($select);
		$this->db->from($table);
		$this->db->where($where_array);
		if(!empty($join_array))
		{
			foreach($join_array as $key=>$value){
				$this->db->join($key, $value, 'left');
			}
		}
		$query = $this->db->get();

		return $query->result();
	}

	//	Function for custom query
	public function customQueryResult($sql)
	{
		$result = $this->db->query($sql)->result();
		return $result;
	}
	
	//	Function for custom query for single row
	public function customQueryRow($sql)
	{
		$result = $this->db->query($sql)->row();
		return $result;
	}
	
	// Function for select data
	public function getData($table,$where='', $order_by = false, $order = false)
	{
		if(!empty($where))
		{
			$this->db->where($where);
		}
		
		if(!empty($order_by))
		{
			$this->db->order_by($order_by, $order); 	
		}
		
		$result = $this->db->get($table)->result();
		return $result;
	}

	//	Functin for select data for single row
	public function getDataSingleRow($table,$where, $order_by = false, $order = false)
	{
		if(!empty($where))
		{
			$this->db->where($where);
		}
		
		if(!empty($order_by))
		{
			$this->db->order_by($order_by, $order); 	
		}
		
		$result = $this->db->get($table)->row();
		return $result;
	}
	
	public function insertData($table,$data)
	{ 
		if($this->db->insert($table,$data))
		{
			return $this->db->insert_id();
		}
	}

	// ==========
	public function insertvalues($data)
	{ 
		$this->db->insert('amaz_aug',$data);
			
			return $this->db->insert_id();
		
	}
	// ==================
	public function updateData($table,$data,$where_array)
	{ 
	    $this->db->where($where_array);
		if($this->db->update($table,$data)){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function deleteData($table,$where)
	{ 
		
		$this->db->where($where);
		if($this->db->delete($table))
		{
			return true;
		}
	}
	
	
	public function clearSessionData() {
		
		foreach($this->session->userdata as $sess_var){
			unset($sess_var);
		}
	}
	
	public function check_permission($msg)
	{
		$whereUsrType = array('userid_fk'=>$this->session->userdata('user_id'));
			$typeID = $this->common_model->getDataSingleRow('ct_user_groups',$whereUsrType);
			
			if(!empty($typeID))
			{
				$table1 = $this->tbl_ct_user_privileges;
				
				$where = array('ct_user_privilege_users.ugpid_fk'=>$typeID->groupid_fk,
						   'ct_user_privileges.upriv_name'=>$msg);
	
				$this->db->select('*');
				$this->db->from('ct_user_privilege_users');
				$this->db->where($where);
				$this->db->join('ct_user_privileges','ct_user_privilege_users.upriv_fk=ct_user_privileges.upriv_id');
				$query = $this->db->get();
				
				return $privResults = $query->row();
				
			}	
	}
	
	public function weekly_notify($login_date = false)
	{
		$startTimeStamp = strtotime($login_date);
		$endTimeStamp = strtotime(date('Y-m-d'));
		
		$timeDiff = abs($endTimeStamp - $startTimeStamp);
		
		$numberDays = $timeDiff/86400;  // 86400 seconds in one day
		
		// and you might want to convert to integer
		return $numberDays = intval($numberDays);
	}
	
}