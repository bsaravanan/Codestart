<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Common extends CI_Model 
{
	
	function controller($table,$id,$post,$jdata,$bdata,$field)
	{
		
		// Controller content
		
		//echo "<pre>"; print_r($post); exit;
		
		if($post)
		{
			
			$validation='';	
				
			foreach($this->db->query("SHOW COLUMNS FROM ".$table."")->result_array() as $fieldname => $db)
			{
				// Avoid Auto increment field
				
				if($db['Extra']!='auto_increment')
				{
					// check is display name available
					if($post[$db['Field'].'_display'])
					{
						$display = $post[$db['Field'].'_display'];
					}
					else 
					{
						$display = $db['Field'];
					}
					
					// check is visible
					if($post[$db['Field'].'_visible'])
					{
					
						if($post[$db['Field'].'_validation'])
						{
							
							$cond='';
							
							foreach($post[$db['Field'].'_validation'] as $validate)
							{
								
								if($validate=='required')
								{
									$cond.='required|';
								}
								if($validate=='minlength')
								{
									$cond.='min_length[10]|';
								}
								if($validate=='maxlength')
								{
									$cond.='max_length[10]|';
								}
								if($validate=='num')
								{
									$cond.='numeric|';
								}
								if($validate=='remote')
								{
									 
									
									$cond.='is_unique['.$table.'.'.$db['Field'].']|';
								}
								
								
								
							}
							
							$set = substr($cond,0,-1);
							
							if($this->input->post($db['Field'].'_type')=='imageupload')
							{
									
								
								
$validation.= 'if(!isset($_FILES[\''.$db['Field'].'\'][\'name\'])) {
	
if(!isset($edit))
{
	
	
$this->form_validation->set_rules(\''.$db['Field'].'\', \''.$display.'\', \''.$set.'\');

}

	
}
else
{
	$this->form_validation->set_rules(\''.$db['Field'].'\', \''.$db['Field'].'\', \'trim\');
}
';
								
							}
							else {
								
								
								if($this->input->post($db['Field'].'_type')=='checkbox' || $this->input->post($db['Field'].'_type')=='radio' || $this->input->post($db['Field'].'_type')=='multiselect')
								{
									$array = '[]';
								}
								else {
									$array = '';
								}
								
$validation.= '$this->form_validation->set_rules(\''.$db['Field'].$array.'\', \''.$display.'\', \''.$set.'\');
';
							}
							
							
			
							
						}
					
					}
					
				}
				
			}
			
		}
		
		
				
		//echo $validation; exit;		

		
$controller_content = '
<?php defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');

class '.ucfirst($table).' extends CI_Controller {

 
	public function __construct()
	{
 		parent::__construct();
		
		$this->load->helper(array(\'form\', \'url\'));

        $this->load->library(\'form_validation\');
 	
 	
	}
 	public function jgetdata()
	{
		'.$jdata.'
	}
	public function bgetdata()
	{
		'.$bdata.'
	}
 
	public function index()
	{
		$data[\'data\'] = $this->db->select(\''.$field.'\')->from(\''.$table.'\')->where(\'status !=\',\'Delete\')->get()->result_array();
		
		$data[\'view\'] = "list";
		
		$this->load->view(\''.$table.'\',$data);
						
	}
			
	public function add()
	{
		
		if($this->input->post() || count($_FILES)>0)
		{
			
			$insertdata = array();
			
			
			foreach($this->db->query("SHOW COLUMNS FROM '.$table.'")->result_array() as $fieldname => $db)
			{
				if($db[\'Extra\']!=\'auto_increment\')
				{
					
					
					
					if($this->input->post($db[\'Field\']))
					{
					
					if(is_array($this->input->post($db[\'Field\'])))
					{	
						$combine = \'\';
						
						foreach($this->input->post($db[\'Field\']) as $checked)
						{
							$combine.=$checked.\',\';
						}
						
						$value = substr($combine,0,-1);

						$insertdata[$db[\'Field\']] = $value; 
					}
					else
					{
						$insertdata[$db[\'Field\']] = $this->input->post($db[\'Field\']);
					}
					}
						
					if(isset($_FILES[$db[\'Field\']][\'name\'])) {
						
						$config[\'upload_path\']          = \'upload/\';
		                $config[\'allowed_types\']        = \'gif|jpg|png\';
		                $config[\'max_size\']             = 1000;
		                //$config[\'max_width\']            = 1024;
		                //$config[\'max_height\']           = 768;
		
		                $this->load->library(\'upload\', $config);
		
		                if ( ! $this->upload->do_upload($db[\'Field\']))
		                {
		                        $data[$db[\'Field\'].\'_error\'] = $this->upload->display_errors();
		
		                       	$data[\'view\'] = "add";
		
								$this->load->view("'.$table.'",$data);
		                }
		                else
		                {
		                		$upload_data = $this->upload->data(); 
 		 						
 		 						$file_name =   $upload_data[\'file_name\'];
								
								$file_path = $upload_data[\'full_path\']; // get file path
								
								chmod($file_path,0777);
		                		
		                		$insertdata[$db[\'Field\']] = $file_name; 
								
		                }
						
					}
						
						
					//$insertdata[$db[\'Field\']] = $this->input->post($db[\'Field\']);
				}
			}';
			
			if($validation!='')
			{
				
					
				$controller_content.= $validation;
				
				
				
                $controller_content.= 'if ($this->form_validation->run() == TRUE)
                {
                		$insertdata[\'status\'] = \'Active\'; 
                	';
  		
			
				$controller_content.= '$this->db->insert(\''.$table.'\',$insertdata);
				redirect("'.$table.'");';
			
			                      
                $controller_content.='}
                else
                {
					$data[\'view\'] = "add";
		
					$this->load->view("'.$table.'",$data);
				}';
	
				
			}
		$controller_content.='
		}
		else {
			
			$data[\'view\'] = "add";
		
			$this->load->view("'.$table.'",$data);
				
		}
		
		
	}
	
	public function edit($edit)
	{
		
		$id = $this->db->query("SHOW COLUMNS FROM '.$table.' where extra like \'%auto_increment%\'")->row_array()[\'Field\'];
		
		
		if($this->input->post() || count($_FILES)>0)
		{
			
			$updatedata = array();
			
			foreach($this->db->query("SHOW COLUMNS FROM '.$table.'")->result_array() as $fieldname => $db)
			{
				if($db[\'Extra\']!=\'auto_increment\')
				{
					
					if(is_array($this->input->post($db[\'Field\'])))
					{	
						$combine = \'\';
						
						foreach($this->input->post($db[\'Field\']) as $checked)
						{
							$combine.=$checked.\',\';
						}
						
						$value = substr($combine,0,-1);

						$updatedata[$db[\'Field\']] = $value;
					}
					else
					{
						$updatedata[$db[\'Field\']] = $this->input->post($db[\'Field\']);
					}
					
					if(isset($_FILES[$db[\'Field\']][\'name\']) && $_FILES[$db[\'Field\']][\'name\']!=\'\') {
						
						$config[\'upload_path\']          = \'upload/\';
		                $config[\'allowed_types\']        = \'gif|jpg|png\';
		                $config[\'max_size\']             = 1000;
		                //$config[\'max_width\']            = 1024;
		                //$config[\'max_height\']           = 768;
		
		                $this->load->library(\'upload\', $config);
		
		                if ( ! $this->upload->do_upload($db[\'Field\']))
		                {
		                        $data[$db[\'Field\'].\'_error\'] = $this->upload->display_errors();
		
		                       	$data[\'view\'] = "add";
								
								$data[\'edit\'] = $this->db->select(\'*\')->from(\''.$table.'\')->where(\''.$id.'\',$edit)->get()->row_array();
								
								$this->load->view("'.$table.'",$data);
		                }
		                else
		                {
		                		$upload_data = $this->upload->data(); 
 		 						
 		 						$file_name =   $upload_data[\'file_name\'];
		                		
		                		$updatedata[$db[\'Field\']] = $file_name; 
								
								$file_path = $upload_data[\'full_path\']; // get file path
								
								chmod($file_path,0777);
								
								$dbfile = $this->db->select(\'*\')->from(\''.$table.'\')->where(\''.$id.'\',$edit)->get()->row_array()[\'\'.$db[\'Field\'].\'\'];
								
								if(file_exists(\'upload/\'.$dbfile.\'\'))
								{
								 	unlink(\'upload/\'.$dbfile.\'\');
								}
								
								
		                }
						
					}
					else if(isset($_FILES[$db[\'Field\']][\'name\']) && $_FILES[$db[\'Field\']][\'name\']==\'\') {
						unset($updatedata[$db[\'Field\']]);
					}
					
					
					//$updatedata[$db[\'Field\']] = $this->input->post($db[\'Field\']);
				}
			}';
			
			if($validation!='')
			{
				
				
				
				
				
					
				$controller_content.= $validation;
				
				
				
                $controller_content.= 'if ($this->form_validation->run() == TRUE)
                {
                		unset($insertdata[\'status\']);
                		//$insertdata[\'status\'] = \'Active\';
                ';
  		
			
				$controller_content.= '$this->db->where("'.$id.'",$edit);			
			$this->db->update("'.$table.'",$updatedata);
			redirect("'.$table.'");	';
			
			                      
                $controller_content.='}
                else
                {
					$data[\'edit\'] = $this->db->select(\'*\')->from(\''.$table.'\')->where(\''.$id.'\',$edit)->get()->row_array();
		
					$data[\'view\'] = "add";
					
					$this->load->view("'.$table.'",$data);
				}';
	
				
			}
		$controller_content.='
		}
		else {
			
			$data[\'edit\'] = $this->db->select(\'*\')->from(\''.$table.'\')->where(\''.$id.'\',$edit)->get()->row_array();
		
		$data[\'view\'] = "add";
		
		$this->load->view("'.$table.'",$data);
				
		
			
				
			
		}
		
		
		
		
	}

	/*
	public function delete()
		{
			
			$id = $this->db->query("SHOW COLUMNS FROM '.$table.' where extra like \'%auto_increment%\'")->row_array()[\'Field\'];
			
			if($this->input->post())
			{
				
				
				
				$delete = $this->input->post(\'id\');
				
				foreach($this->db->query("SHOW COLUMNS FROM '.$table.'")->result_array() as $fieldname => $db)
				{
					// Avoid Auto increment field
					
					if($db[\'Extra\']!=\'auto_increment\')
					{
						
						$dbfile = $this->db->select(\'*\')->from(\''.$table.'\')->where(\''.$id.'\',$delete)->get()->row_array()[\'\'.$db[\'Field\'].\'\'];
									
						if(file_exists(\'upload/\'.$dbfile.\'\'))
						{
							 unlink(\'upload/\'.$dbfile.\'\');
						}
						
					}
				}
				
				
				$this->db->where("'.$id.'",$delete);			
				$this->db->delete("'.$table.'");
			}
			
		}*/
	
	
	
		/*
		public function deleteall()
				{
				
				$id = $this->db->query("SHOW COLUMNS FROM '.$table.' where extra like \'%auto_increment%\'")->row_array()[\'Field\'];
				
				if($this->input->post())
				{
					
					
					
					$delete = $this->input->post(\'ids\');
					
					$explode = explode(",",$delete); 
					
					foreach($explode as $ids)
					{
						
						
						
					
					foreach($this->db->query("SHOW COLUMNS FROM '.$table.'")->result_array() as $fieldname => $db)
					{
						// Avoid Auto increment field
						
						if($db[\'Extra\']!=\'auto_increment\')
						{
							
							$dbfile = $this->db->select(\'*\')->from(\''.$table.'\')->where(\''.$id.'\',$ids)->get()->row_array()[\'\'.$db[\'Field\'].\'\'];
										
							if(file_exists(\'upload/\'.$dbfile.\'\'))
							{
								 unlink(\'upload/\'.$dbfile.\'\');
							}
							
						}
					}
					
					
					$this->db->where("'.$id.'",$ids);			
					$this->db->delete("'.$table.'");
					
					}
					
				}
				
			}*/
		


	
	public function statuschange()
	{
		
		if($this->input->post())
		{
			
			$id = $this->input->post(\'id\');
			
			$status = $this->input->post(\'status\');
			
			$data = array( \'status\' => $status );
			
			$this->db->where("'.$id.'",$id);			
			
			$this->db->update("'.$table.'",$data);
		}
		
	}
			
			
	public function statuschangeall()
	{
			
		$id = $this->db->query("SHOW COLUMNS FROM '.$table.' where extra like \'%auto_increment%\'")->row_array()[\'Field\'];
		
		if($this->input->post())
		{
			
			
			
			$delete = $this->input->post(\'ids\');
			
			$status = $this->input->post(\'status\');
			
			$explode = explode(",",$delete); 
			
			$data = array( \'status\' => $status );
			
			
			foreach($explode as $ids)
			{
				
			
			$this->db->where("'.$id.'",$ids);			
			
			$this->db->update("'.$table.'",$data);
			
			}
			
		}
		
		
		
	}
	
	
	
		
}
		
?>';
	
	return $controller_content;
	
		
	}
}


