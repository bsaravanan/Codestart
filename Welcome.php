<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	 public function __construct()
	 {
	 	parent::__construct();
	 	
	 	$this->load->helper('array');
		$this->load->helper('captcha');	
		$this->load->library('session');
		$this->load->helper('cookie');	
		$this->load->helper('date');	
		$this->load->helper('directory');
		$this->load->helper('download');
		$this->load->helper('email');
		$this->load->helper('file');
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('html');
		$this->load->helper('inflector');
		$this->load->helper('language');
		
		$this->lang->load('message','german');
		$this->load->helper('number');
		$this->load->helper('path');
		$this->load->helper('security');
		$this->load->helper('smiley');
        $this->load->library('table');
		
        $this->load->helper('string');
		$this->load->helper('text');
		$this->load->helper('typography');
		$this->load->helper('xml');
		$this->load->dbutil();
		$this->load->model('common');
	 }
	 
	 public function validatedata()
	{
		
	    // $filename='data.json';
// 
		// $str = file_get_contents(APPPATH.$filename);
// 	
		// //print "<pre>";
// 		
		// //print_r(json_decode($str));
// 		
		// $data = json_decode($str,true); 
// 		
		// //print "</pre>";
// 		
		// //print "<pre>";
// 		
		// //print_r(json_decode($str,true));
// 		
		// //print "</pre>";
// 		
// 		
		// $page = $data; 
// 		
		// echo "<pre>"; print_r($page['listingResults'][0]['vehicle']); 
// 		
// 		
		// exit;
// 		
// 		
// 		
// 		
// 		
		// if($_POST)
		// {
			// echo "string";
// 		
		// exit;
		// $this->form_validation->set_error_delimiters('', '');
// 		
		// $this->form_validation->set_rules('first','First Name', 'required');
// 		
		// $this->form_validation->set_rules('last','Last Name', 'required');
// 		
// 		
		// if ($this->form_validation->run() == FALSE) {
// 		   
		    // echo validation_errors();
			// exit;
		// } 
		// else {
		  // $data['frist_name']=$this->input->post('first');
		  // $data['last_name']=$this->input->post('last');
		  // // Then pass $data  to Modal to insert bla bla!!
// 		
// 			
// 		
		// }
// 		
		// }
// 		
		$this->load->view('validatedata');
		
		
	} 
	
	public function CreateStudentsAjax() {

        
        
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('stdroll', 'Roll Number', 'required');
        $this->form_validation->set_rules('stdname', 'Name', 'required');
        
        if ($this->form_validation->run()) {

            //$this->welcome_model->InsertStudents();
            
			echo json_encode("Oks");
			
			//redirect('welcome/csvview');
			
        } else {

            $data = array(
                'roll' => form_error('stdroll'),
                'name' => form_error('stdname')
            );

            echo json_encode($data);
        }
    }
	
	
	public function createviews()
	{
		
		$table = $this->input->post('table');
		
		// View Content
		
		
		
			
			
		

		$htmlcontent = '';
  		
		//echo "<pre>"; print_r($this->input->post());
		
		//exit;
		
		$fieldsposted='';
		$columns='';
		$bcolumns='';
		$search='';
		$bsearch='';
		$nest='';
		$jdata='';
		$bdata='';
		$i=0;
		
		foreach($this->db->query("SHOW COLUMNS FROM ".$table."")->result_array() as $fieldname => $db)
		{
			// Avoid auto increment field
			
			
			if($db['Extra']=='auto_increment')
			{
				$id = $db['Field'];
			}
			
			if($db['Extra']!='auto_increment')
			{	
				if($this->input->post($db['Field'].'_visible'))
				{
					$fieldsposted.=$db['Field'].','; 
					
						
						
						if($i==0)
						{
							$columns.= ' 0 => \''.$id.'\',';
							
							$bcolumns.= ' \'check\' =>\'<input name="checkid[]" value="\'.$row["'.$id.'"].\'" class="sub_chk" type="checkbox">\',';
							
						}
						$j=$i+1;
							
						$columns.= ' '.$j.' =>\''.$db['Field'].'\',';	
						
					
					
					
					
					if($this->input->post($db['Field'].'_type')=='imageupload')
					{
						
						$bcolumns.= ' \''.$db['Field'].'\' =>"<img src=\'".base_url()."upload/".$row[\''.$db['Field'].'\']."\' width=\'50\' height=\'50\' />",';
						
					
						
					}
					else {
							$bcolumns.= ' \''.$db['Field'].'\' =>$row[\''.$db['Field'].'\'],';
					}
					
					
					
					if($i==0) {
						
						$bsearch.='
						$cond.=" AND ( '.$db['Field'].' LIKE \'".$r[\'search\']."%\' ";';
						
						$search.='
						$sql.=" AND ( '.$db['Field'].' LIKE \'".$requestData[\'search\'][\'value\']."%\' ";';
					}
					else {
						$bsearch.='
						$cond.=" OR '.$db['Field'].' LIKE \'".$r[\'search\']."%\' ";';
					
						$search.='
						$sql.=" OR '.$db['Field'].' LIKE \'".$requestData[\'search\'][\'value\']."%\' ";';
					}
					
					
					if($this->input->post($db['Field'].'_type')=='imageupload')
					{
					$nest.=' 
					$nestedData[] = "<img src=\'".base_url()."upload/".$row[\''.$db['Field'].'\']."\' width=\'50\' height=\'50\' />"; 
					';
						
					}
					else {
							$nest.=' 
						$nestedData[] = $row["'.$db['Field'].'"]; 
						';
					}
					
					
					
					
					
				}
				$i++;
			}
		
		
		}
		
		if($this->input->post($table.'_dadd')=='yes')
		{
			$displayadd = '<a href="<?php echo base_url()."'.$table.'/add" ?>" class="btn btn-default" >Add '.$table.'</a>';
		}
		else {
			$displayadd = '';
		}
		if($this->input->post($table.'_dedit')=='yes')
		{
			$displayedit = '<a href=".base_url()."'.$table.'/edit/".$row["'.$id.'"]." class=\'btn btn-primary\' >Edit</a>';
		}
		else {
			$displayedit = '';
		}
		if($this->input->post($table.'_ddelete')=='yes')
		{
			$displaydelete = '<button  class=\'btn btn-danger\' onclick=\"deleteid(".$row["'.$id.'"].");\" >Delete</button>';
		}
		else {
			$displaydelete = '';
		}
		
		$field = $fieldsposted.$id.',status';
		
		$columns = 'array( '.$columns.' );';
		
		if($this->input->post($table.'_dedit')=='yes' || $this->input->post($table.'_ddelete')=='yes' )
		{
		
		$bcolumns = 'array( '.$bcolumns.' \'status\' => $status, \'action\' => $action );';
		
		}
		else
		{
		
		$bcolumns = 'array( '.$bcolumns.' \'status\' => $status );';
		
		}
		
		
		
		$bsearchs = substr($bsearch,0,-2);
		
		$bsearched = $bsearchs.' ) ";';
		
		$searchs = substr($search,0,-2);
		
		$searched = $searchs.' ) ";';
		
		if($this->input->post($table.'_tablepage')=='spage')
		{
		
		if($this->input->post($table.'_tabletype')=='jtable')
		{
		
			// storing  request (ie, get/post) global array to a variable  
			$jdata.='
			$requestData= $this->input->post();
			';
			
			
			$jdata.='
			$columns = '.$columns.'
			'; 
			
			// getting total number records without any search
			$jdata.='
			$sql = "SELECT '.$field.' ";
			';
			$jdata.='
			$sql.=" FROM '.$table.' WHERE status!=\'Delete\'";
			';
			$jdata.='
			$query=$this->db->query($sql)->result_array();
			';
			$jdata.='
			$totalData = $this->db->query($sql)->num_rows();
			';
			$jdata.='
			$totalFiltered = $totalData; 
			'; 
			
			
			$jdata.='
			$sql = "SELECT '.$field.' ";
			';
			
			$jdata.='
			$sql.=" FROM '.$table.' WHERE status!=\'Delete\'";
			';
			$jdata.='
			if( !empty($requestData[\'search\'][\'value\']) ) {
			';   
				
			$jdata.= $searched;
				
			$jdata.='
			}
			';
			
			$jdata.='
			$query=$this->db->query($sql)->result_array();
			';
			$jdata.='
			$totalFiltered = $this->db->query($sql)->num_rows();
			';
 
			$jdata.='$sql.=" ORDER BY ". $columns[$requestData[\'order\'][0][\'column\']]."   ".$requestData[\'order\'][0][\'dir\']."  LIMIT ".$requestData[\'start\']." ,".$requestData[\'length\']."   ";';
			
			$jdata.='
			$query=$this->db->query($sql)->result_array();
			';
				
			
			$jdata.='
			$data = array();
			';
			$jdata.='
			foreach ($query as $row) {
				
			
				$nestedData=array();
				
				$nestedData[] = \'<input name="checkid[]" value="\'.$row["'.$id.'"].\'" class="sub_chk" type="checkbox">\';
				
			'; 
			
			$jdata.=''.$nest.'
			';
			
			$jdata.='
			if($row[\'status\']=="Active")
			{
				$status="<button  class=\'btn btn-success\' onclick=\"status(".$row["'.$id.'"].",\'Active\');\" >Active</button>";
			}
			elseif($row[\'status\']=="Inactive")  {
				$status="<button  class=\'btn btn-danger\' onclick=\"status(".$row["'.$id.'"].",\'Inactive\');\" >Inactive</button>";
			}
			
			$nestedData[] = \'\'.$status.\'\';
			
			';
			
			
			if($this->input->post($table.'_dedit')=='yes' || $this->input->post($table.'_ddelete')=='yes' )
			{
			
			$jdata.='
			
			
			
			$nestedData[] = "'.$displayedit.' &nbsp; '.$displaydelete.'";
			';
				
			}
			
				$jdata.='$data[] = $nestedData;
			}
			
			
			
			$json_data = array(
						"draw"            => intval( $requestData[\'draw\'] ),   
						"recordsTotal"    => intval( $totalData ), 
						"recordsFiltered" => intval( $totalFiltered ), 
						"data"            => $data   
						);
			
			echo json_encode($json_data); ';
					
					
				
				
				
				
			
		
		
			
			// List Content
						
$view_content_list = '
<div style="display:<?php if($view==\'list\') { echo "block"; } else { echo "none"; } ?>">
 <h2>'.ucfirst($table).' List</h2>
 <div id="toolbar" style="" class="form-inline">'.$displayadd.'</div> 
  
<table style="border-collapse: inherit !important;" id="example" class="table table-bordered table-striped" >
<thead>
       <tr>
        <th> <input name="select_all" value="1" id="select_all" type="checkbox"></th>
        '; 
							

// List Content head fields

$list_fields_body = '';

foreach($this->db->query("SHOW COLUMNS FROM ".$table."")->result_array() as $fieldname => $db)
{
	// if display name available
	if($this->input->post($db['Field'].'_display'))
	{
		$display = $this->input->post($db['Field'].'_display');
	}
	else {
		$display = $db['Field'];
	}
	// Avoid auto increment field
	if($db['Extra']!='auto_increment')
	{	
		if($this->input->post($db['Field'].'_visible'))
		{
			
			$list_fields_thead = '<th  data-field="'.$db['Field'].'" >'.$display.'</th>';
			$view_content_list.= $list_fields_thead;
			$list_fields_body.= '<td> ".$value[\''.$db['Field'].'\']."</td>';
		}
	}
	// get auto increment field
	if($db['Extra']=='auto_increment')
	{
		$id = $db['Field'];
	}
	
}

$view_content_list.='
<th>Status</th>';


// list content body

if($this->input->post($table.'_dedit')=='yes' || $this->input->post($table.'_ddelete')=='yes' )
			{
			
$view_content_list.='
<th>Action</th>';
			
			}

$view_content_list.='</tr>
</thead>
</table>

<button type="button" class="btn btn-success pull-left active_all">Active Selected</button>&nbsp;<button type="button" class="btn btn-danger pull-left inactive_all">Inactive Selected</button>&nbsp;<button type="button" class="btn btn-primary pull-left delete_all">Delete Selected</button>
</div>
<script type="text/javascript" language="javascript" >
			$(document).ready(function() {
				var dataTable = $(\'#example\').DataTable( {
					//"processing": true,
					"serverSide": true,
					aoColumnDefs: [
					  {
					     bSortable: false,
					     aTargets: [ 0,-1,-2 ]
					  }
					],
					"ajax":{
						url :"<?php echo base_url().\''.$table.'/jgetdata\'  ?>", 
						type: "post",  
						error: function(){  
							$("#example").html("");
							$("#example").append(\'<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>\');
							//$("#employee-grid_processing").css("display","none");
							
						}
					}
				} );
			} );
		</script>

'; // list end
			
		}

		if($this->input->post($table.'_tabletype')=='btable')
		{
			
			$bdata.= '
			$r = $this->input->get();
			';
			
			$bdata.= '
			$order = $r[\'order\'];
			';
			
			$bdata.= '
			$limit = $r[\'limit\'];
			';
			
			$bdata.= '
			$offset = $r[\'offset\'];
			';
			
			
			
			$bdata.= '
			if(isset($r[\'search\']))
			{
			$search = $r[\'search\'];
			}
			';
			
			
			$bdata.= '
			$cond="";
			';
			
			$bdata.= '
			if(isset($search)){
				
				'.$bsearched.'
				
			}
			
			$cond1="";
			
			if(isset($order))
			{
					
				if(isset($r[\'sort\']))
				{
					$sort = $r[\'sort\'];
				}
				
				
				$cond1="ORDER BY '.$id.' ".$order."";
			}
			
			$cond2="limit ".$limit." offset ".$offset."";
			';
			
			$bdata.='
			$sql = "SELECT '.$field.' ";
			';
			$bdata.='
			$sql.=" FROM '.$table.' where status!=\'Delete\'";
			';
			$bdata.='
			$data=$this->db->query($sql)->result_array();
			';
			$bdata.='
			$num = $this->db->query($sql)->num_rows();	
			
			// limit records 
			
			$sql.= " ".$cond1."  ".$cond2."";
			';
			
			$bdata.='
			$data=$this->db->query($sql)->result_array();
			';
			
			
			// filter records
			
			
			$bdata.='
			if(isset($search) && $search!=\'\'){
			';
			
			$bdata.='
			$sql = "SELECT '.$field.' ";
			';
			$bdata.='
			$sql.=" FROM '.$table.' WHERE status!=\'Delete\' ".$cond." ".$cond1." ";
			';
			
			$bdata.='
			$num = $this->db->query($sql)->num_rows();	
			';
			
			
			$bdata.='
			$sql.=" ".$cond2." ";
			';
			
			
			
			$bdata.='
			$data=$this->db->query($sql)->result_array();
			';
			
			
			$bdata.='
			}
			';
			
			
			
			
		if($this->input->post($table.'_dedit')=='yes' || $this->input->post($table.'_ddelete')=='yes' )
			{
			
			$bedit='
			
			
			
			$action = "'.$displayedit.' &nbsp; '.$displaydelete.'";
			';
				
			}
		
		
		//echo $query; exit;
		
		
		
		
		$bdata.='
				$datas = [];
		
				foreach($data as $row)
				{';
		
		$bdata.='
			if($row[\'status\']=="Active")
			{
				$status="<button  class=\'btn btn-success\' onclick=\"status(".$row["'.$id.'"].",\'Active\');\" >Active</button>";
			}
			elseif($row[\'status\']=="Inactive")  {
				$status="<button  class=\'btn btn-danger\' onclick=\"status(".$row["'.$id.'"].",\'Inactive\');\" >Inactive</button>";
			}
			
			
			
			';
		
		$bdata.=''.$bedit.'';		
				
					
		$bdata.='			
					$datas[] = '.$bcolumns.'
					
				}
				
				
				
				$json_data = array(
				                "total"   => $num,
				                "rows"    => $datas,
				                
				                
				            );
				echo json_encode($json_data);
				
				exit;
				';
		
			
			
			//echo $bdata; exit;
			// List Content
						
$view_content_list = '
<div style="display:<?php if($view==\'list\') { echo "block"; } else { echo "none"; } ?>">
 <h2>'.ucfirst($table).' List</h2>
  <div id="toolbar"  class="form-inline">
  	'.$displayadd.'
  </div>
  
<table class="table-striped" style="border-collapse: inherit !important;"  data-toggle="table"  
	   data-url="<?php echo base_url()."'.$table.'/bgetdata"; ?>"
       data-pagination="true"
       data-side-pagination="server"
       data-page-list="[5, 10, 20, 50, 100]"
       data-search="true"
       data-sort-name="'.$id.'"
       data-sort-order="desc"
	   data-toolbar="#toolbar">
<thead>
       <tr>
       <th data-field="check" ><input name="select_all" value="1" id="select_all" type="checkbox"></th>
       '; 
							

// List Content head fields

$list_fields_body = '';

foreach($this->db->query("SHOW COLUMNS FROM ".$table."")->result_array() as $fieldname => $db)
{
	// if display name available
	if($this->input->post($db['Field'].'_display'))
	{
		$display = $this->input->post($db['Field'].'_display');
	}
	else {
		$display = $db['Field'];
	}
	// Avoid auto increment field
	if($db['Extra']!='auto_increment')
	{	
		if($this->input->post($db['Field'].'_visible'))
		{
			$list_fields_thead = '<th data-sortable="true" data-field="'.$display.'" >'.$display.'</th>';
			$view_content_list.= $list_fields_thead;
			$list_fields_body.= '<td> ".$value[\''.$db['Field'].'\']."</td>';
		}
	}
	// get auto increment field
	if($db['Extra']=='auto_increment')
	{
		$id = $db['Field'];
	}
	
}

$view_content_list.='
<th data-field="status">Status</th>';

if($this->input->post($table.'_dedit')=='yes' || $this->input->post($table.'_ddelete')=='yes' )
			{
			
$view_content_list.='
<th data-field="action">Action</th>';
			
			}
// list content body

$view_content_list.='
</tr>
</thead>

</table>
<button type="button" class="btn btn-success pull-left active_all">Active Selected</button>&nbsp;<button type="button" class="btn btn-danger pull-left inactive_all">Inactive Selected</button>&nbsp;<button type="button" class="btn btn-primary pull-left delete_all">Delete Selected</button>
</div>'; // list end
			
		}


}
		
		
		
		
		
		
		
		if($this->input->post($table.'_tablepage')=='cpage')
		{
		
		if($this->input->post($table.'_tabletype')=='jtable')
		{
			
			// List Content
						
$view_content_list = '
<div style="display:<?php if($view==\'list\') { echo "block"; } else { echo "none"; } ?>">
 <h2>'.ucfirst($table).' List</h2>
  
  
<table id="example" class="table table-striped table-bordered" >
<thead>
       <tr>
       <th><input name="select_all" value="1" id="select_all" type="checkbox"></th>
       '; 
							

// List Content head fields

$list_fields_body = '';

foreach($this->db->query("SHOW COLUMNS FROM ".$table."")->result_array() as $fieldname => $db)
{
	// if display name available
	if($this->input->post($db['Field'].'_display'))
	{
		$display = $this->input->post($db['Field'].'_display');
	}
	else {
		$display = $db['Field'];
	}
	// Avoid auto increment field
	if($db['Extra']!='auto_increment')
	{	
		if($this->input->post($db['Field'].'_visible'))
		{
			if($this->input->post($db['Field'].'_type')=='imageupload')
			{
				$list_fields_body.= '<td> <img src=\'".base_url()."upload/".$value[\''.$db['Field'].'\']."\' width=\'50\' height=\'50\' /> </td>';
			}
			else {
				$list_fields_body.= '<td> ".$value[\''.$db['Field'].'\']."</td>';
			}
			$list_fields_thead = '<th>'.$display.'</th>';
			$view_content_list.= $list_fields_thead;
			
		}
	}
	// get auto increment field
	if($db['Extra']=='auto_increment')
	{
		$id = $db['Field'];
	}
	
}

$view_content_list.='
<th>Status</th>';

if($this->input->post($table.'_dedit')=='yes' || $this->input->post($table.'_ddelete')=='yes' )
			{
			
$view_content_list.='
<th>Action</th>';
			
			}
			if($this->input->post($table.'_dedit')=='yes')
			{
				$editd= '<a href=".base_url()."'.$table.'/edit/".$value["'.$id.'"]." class=\'btn btn-primary\' >Edit</a>';
			}
			else {
				$editd= '';
			}
			if($this->input->post($table.'_ddelete')=='yes')
			{
				$deleted= '<button  class=\'btn btn-danger\' onclick=\"deleteid(".$value["'.$id.'"].");\" >Delete</button>';
			}
			else {
				$deleted= '';
			}
// list content body
if($this->input->post($table.'_dedit')=='yes' || $this->input->post($table.'_ddelete')=='yes' )
			{
				
				
				
				
				
				$jedit='<td> '.$editd.' &nbsp; '.$deleted.' </td>';
			}
else {
	
	$jedit='';
}

$view_content_list.='
</tr>
</thead>
<tbody>
	<?php 

	  if(is_array($data))
	  {
	  	foreach ($data as $key => $value) 
	  	{
	  		
			if($value[\'status\']=="Active")
			{
				$status="<button  class=\'btn btn-success\' onclick=\"status(".$value["'.$id.'"].",\'Active\');\" >Active</button>";
			}
			elseif($value[\'status\']=="Inactive") {
				$status="<button  class=\'btn btn-danger\' onclick=\"status(".$value["'.$id.'"].",\'Inactive\');\" >Inactive</button>";
			}
			
			echo "<tr><td><input name=\'checkid[]\' value=\'".$value[\''.$id.'\']."\' class=\'sub_chk\' type=\'checkbox\'></td>'.$list_fields_body.'<td>".$status."</td>'.$jedit.'</tr>";
				
		}
	  
	  }
  
   ?>
</tbody>
</table>
<button type="button" class="btn btn-success pull-left active_all">Active Selected</button>&nbsp;<button type="button" class="btn btn-danger pull-left inactive_all">Inactive Selected</button>&nbsp;<button type="button" class="btn btn-primary pull-left delete_all">Delete Selected</button>
</div>

<script>

$(document).ready(function() {
	
    $(\'#example\').DataTable( {
        "dom": \'<"toolbar">frltip\'
        
    } );
     $("div.toolbar").html(\'<div id="toolbar" style="width: 200px; float: left;" class="form-inline">'.$displayadd.'</div>\');
} );


</script>

'; // list end
			
		}

		if($this->input->post($table.'_tabletype')=='btable')
		{
			
			// List Content
						
						       

						
						
						
$view_content_list = '
<div style="display:<?php if($view==\'list\') { echo "block"; } else { echo "none"; } ?>">
 <h2>'.ucfirst($table).' List</h2>
  <div id="toolbar"  class="form-inline">
  	'.$displayadd.'
  </div>
  
<table class"table-striped" style="border-collapse: inherit !important;" data-toggle="table"  data-pagination="true"
       data-page-list="[5, 10, 20, 50, 100]"
       data-search="true"
       data-sort-order="desc"
       data-sort-name="'.$id.'"
	   data-toolbar="#toolbar">
<thead>
       <tr><th style="text-align: center !important;" ><input name="select_all" value="1" id="select_all" type="checkbox"></th>'; 
							

// List Content head fields

$list_fields_body = '';

foreach($this->db->query("SHOW COLUMNS FROM ".$table."")->result_array() as $fieldname => $db)
{
	// if display name available
	if($this->input->post($db['Field'].'_display'))
	{
		$display = $this->input->post($db['Field'].'_display');
	}
	else {
		$display = $db['Field'];
	}
	// Avoid auto increment field
	if($db['Extra']!='auto_increment')
	{	
		if($this->input->post($db['Field'].'_visible'))
		{
			if($this->input->post($db['Field'].'_type')=='imageupload')
			{
				$list_fields_body.= '<td> <img src=\'".base_url()."upload/".$value[\''.$db['Field'].'\']."\' width=\'50\' height=\'50\' /> </td>';
			}
			else {
				$list_fields_body.= '<td> ".$value[\''.$db['Field'].'\']."</td>';
			}
			$list_fields_thead = '<th data-sortable="true" >'.$display.'</th>';
			$view_content_list.= $list_fields_thead;
			//$list_fields_body.= '<td> ".$value[\''.$db['Field'].'\']."</td>';
		}
	}
	// get auto increment field
	if($db['Extra']=='auto_increment')
	{
		$id = $db['Field'];
	}
	
}

$view_content_list.='
<th>Status</th>';

if($this->input->post($table.'_dedit')=='yes' || $this->input->post($table.'_ddelete')=='yes' )
			{
			
$view_content_list.='
<th>Action</th>';
			
			}
			
			if($this->input->post($table.'_dedit')=='yes')
			{
				$editd= '<a href=".base_url()."'.$table.'/edit/".$value["'.$id.'"]." class=\'btn btn-primary\' >Edit</a>';
			}
			else {
				$editd= '';
			}
			if($this->input->post($table.'_ddelete')=='yes')
			{
				$deleted= '<button  class=\'btn btn-danger\' onclick=\"deleteid(".$value["'.$id.'"].");\" >Delete</button>';
			}
			else {
				$deleted= '';
			}
// list content body
if($this->input->post($table.'_dedit')=='yes' || $this->input->post($table.'_ddelete')=='yes' )
			{
				
				
				
				
				
				$jedit='<td> '.$editd.' &nbsp; '.$deleted.' </td>';
			}
else {
	
	$jedit='';
}

$view_content_list.='
</tr>
</thead>
<tbody>
	<?php 

	  if(is_array($data))
	  {
	  	foreach ($data as $key => $value) 
	  	{
	  		if($value[\'status\']=="Active")
			{
				$status="<button  class=\'btn btn-success\' onclick=\"status(".$value["'.$id.'"].",\'Active\');\" >Active</button>";
			}
			elseif($value[\'status\']=="Inactive") {
				$status="<button  class=\'btn btn-danger\' onclick=\"status(".$value["'.$id.'"].",\'Inactive\');\" >Inactive</button>";
			}
			
			echo "<tr><td style=\'text-align: center !important;\'><input name=\'checkid[]\' value=\'".$value[\''.$id.'\']."\' class=\'sub_chk\' type=\'checkbox\'></td>'.$list_fields_body.'<td>".$status."</td>'.$jedit.'</tr>";
				
		}
	  
	  }
  
   ?>
</tbody>
</table>
<button type="button" class="btn btn-success pull-left active_all">Active Selected</button>&nbsp;<button type="button" class="btn btn-danger pull-left inactive_all">Inactive Selected</button>&nbsp;<button type="button" class="btn btn-primary pull-left delete_all">Delete Selected</button>
</div>'; // list end
			
		}


}
		
  		
						



// Add content



// add form start

$validaterules = '';
$validatemessage = ''; 
$view_content_add = '


<div style="display:<?php if($view==\'add\') { echo "block"; } else { echo "none"; } ?>">
  <h2><?php if($this->uri->segment(2)=="edit") { echo "Edit"; } else { echo "Add"; } ?></h2>
  <form name="'.$table.'" id="'.$table.'" method="post" enctype="multipart/form-data" action="<?php if($this->uri->segment(2)=="edit") { $action = "edit"; } else { $action = "Add"; }  echo base_url()."'.$table.'/$action/".$this->uri->segment(3)."" ?>"  >
';

foreach($this->db->query("SHOW COLUMNS FROM ".$table."")->result_array() as $fieldname => $db)
{
	// Avoid Auto increment field
	
	if($db['Extra']!='auto_increment')
	{
		// check is display name available
		if($this->input->post($db['Field'].'_display'))
		{
			$display = $this->input->post($db['Field'].'_display');
		}
		else 
		{
			$display = $db['Field'];
		}
		
		
		// check is visible
		if($this->input->post($db['Field'].'_visible'))
		{
				
				// if input type Image upload
			if($this->input->post($db['Field'].'_type')=='imageupload')
			{
				$view_content_add.= '
<div class="form-group">
   <label for="'.$display.'">'.$display.'</label>
	      <input type="file" autocomplete="off" id="'.$db['Field'].'" name="'.$db['Field'].'" class="" value="" >
<?php if(isset($'.$db['Field'].'_error)) { echo $'.$db['Field'].'_error; } ?>
<?php if($this->uri->segment(3)) { ?>
<span>Old Image</span><br />
<img id="'.$db['Field'].'_edit" src="<?php echo base_url().\'upload/\'.$edit[\''.$db['Field'].'\'] ?>" alt="your image" width="100" height="100"  />
<?php  } ?>
<span id="'.$db['Field'].'_spa" style="display:none;" >Image Preview</span>
<img id="'.$db['Field'].'_pre" src="#" alt="your image" width="100" height="100" style="display:none;" />
	      </div>
	      
	      
	      <script>
	      
	          function readURL'.$db['Field'].'(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $("#'.$db['Field'].'_pre").attr("src", e.target.result);
				$("#'.$db['Field'].'_pre").css({"display":"block"});
				$("#'.$db['Field'].'_spa").css({"display":"block"});
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#'.$db['Field'].'").change(function(){
        readURL'.$db['Field'].'(this);
    });
	      
	      </script>
	      
	      ';		
				// if validation
				if($this->input->post($db['Field'].'_validation[]'))
				{
					$rcondition = '';
					$mcondition = '';
					
					foreach($this->input->post($db['Field'].'_validation[]') as $validate)
					{
						
						
						if($validate=='required')
						{
							$rcondition.= 'required : <?php if($this->uri->segment(2)=="edit") { echo "false"; } else { echo "true"; } ?>,';
							
							$mcondition.= 'required : "'.$display.' field is required",';
						}
						if($validate=='num')
						{
							$rcondition.= 'number : true,';
							
							$mcondition.= 'number : "only numeric",';
						}
						if($validate=='minlength')
						{
							$rcondition.= ' minlength: 10,';
							$mcondition.= 'minlength : "minimum length required is 10",';
						}
						if($validate=='maxlength')
						{
							$rcondition.= 'maxlength : 10,';
							$mcondition.= 'maxlength : "maximum length allowed is 10",';
						}
						if($validate=='remote')
						{
							$rcondition.= 'remote : { url:"'.base_url().'welcome/duplicatecheck", type  : "post", data: { field :\''.$db['Field'].'\', table :\''.$table.'\' } },';
							$mcondition.= 'remote : "Already  exists",';
						}
						
						
						

						
					}

					$validaterules.= $db['Field'].'	: { '.$rcondition.' },'; 
					
					$validatemessage.= $db['Field'].'	: { '.$mcondition.' },';
				}
				
								 
			}
				
				
			// if input type text box
			if($this->input->post($db['Field'].'_type')=='textbox')
			{
				$view_content_add.= '
<div class="form-group">
   <label for="'.$display.'">'.$display.'</label>
	      <input type="text" autocomplete="off" name="'.$db['Field'].'" class="form-control" value="<?php if($this->uri->segment(3)) { echo $edit[\''.$db['Field'].'\']; } else { echo set_value(\''.$db['Field'].'\'); } ?>" >
<?php echo form_error(\''.$db['Field'].'\');  ?>
	      </div>';		
				// if validation
				if($this->input->post($db['Field'].'_validation[]'))
				{
					$rcondition = '';
					$mcondition = '';
					
					foreach($this->input->post($db['Field'].'_validation[]') as $validate)
					{
						
						
						if($validate=='required')
						{
							$rcondition.= 'required : true,';
							
							$mcondition.= 'required : "'.$display.' field is required",';
						}
						if($validate=='num')
						{
							$rcondition.= 'number : true,';
							
							$mcondition.= 'number : "only numeric",';
						}
						if($validate=='minlength')
						{
							$rcondition.= ' minlength: 10,';
							$mcondition.= 'minlength : "minimum length required is 10",';
						}
						if($validate=='maxlength')
						{
							$rcondition.= 'maxlength : 10,';
							$mcondition.= 'maxlength : "maximum length allowed is 10",';
						}
						if($validate=='remote')
						{
							$rcondition.= 'remote : { url:"'.base_url().'welcome/duplicatecheck", type  : "post", data: { field :\''.$db['Field'].'\', table :\''.$table.'\' } },';
							$mcondition.= 'remote : "Already  exists",';
						}
						
						
						

						
					}

					$validaterules.= $db['Field'].'	: { '.$rcondition.' },'; 
					
					$validatemessage.= $db['Field'].'	: { '.$mcondition.' },';
				}
				
								 
			}
			
			// if input type text area
			if($this->input->post($db['Field'].'_type')=='textarea')
			{
				// echo form_error('password'); 

				$view_content_add.= '
<div class="form-group">
   <label for="'.$display.'">'.$display.'</label>
	      <textarea name="'.$db['Field'].'" id="'.$db['Field'].'"   ><?php if($this->uri->segment(3)) { echo $edit[\''.$db['Field'].'\']; } else { echo set_value(\''.$db['Field'].'\'); }  ?></textarea>
	      <?php echo form_error(\''.$db['Field'].'\');  ?>
</div>';		
				// if validation
				// if validation
				if($this->input->post($db['Field'].'_validation[]'))
				{
					$rcondition = '';
					$mcondition = '';
					
					foreach($this->input->post($db['Field'].'_validation[]') as $validate)
					{
						
						
						if($validate=='required')
						{
							$rcondition.= 'required : true,';
							
							$mcondition.= 'required : "'.$display.' field is required",';
						}
						if($validate=='num')
						{
							$rcondition.= 'number : true,';
							
							$mcondition.= 'number : "only numeric",';
						}
						if($validate=='minlength')
						{
							$rcondition.= ' minlength: 10,';
							$mcondition.= 'minlength : "minimum length required is 10",';
						}
						if($validate=='maxlength')
						{
							$rcondition.= 'maxlength : 10,';
							$mcondition.= 'maxlength : "maximum length allowed is 10",';
						}
						if($validate=='remote')
						{
							$rcondition.= 'remote : { url:"'.base_url().'welcome/duplicatecheck", type  : "post", data: { field :\''.$db['Field'].'\', table :\''.$table.'\' } },';
							$mcondition.= 'remote : "Already  exists",';
						}
						
						
						

						
					}

					$validaterules.= $db['Field'].'	: { '.$rcondition.' },'; 
					
					$validatemessage.= $db['Field'].'	: { '.$mcondition.' },';
				}
				
				
				 
			}
			
			
			// if input type editor
			if($this->input->post($db['Field'].'_type')=='editor')
			{
				$view_content_add.= '
<div class="form-group">
   <label for="'.$display.'">'.$display.'</label>
	      <textarea name="'.$db['Field'].'" id="'.$db['Field'].'" ><?php if($this->uri->segment(3)) { echo $edit[\''.$db['Field'].'\']; } else { echo set_value(\''.$db['Field'].'\'); } ?></textarea>
		<?php echo form_error(\''.$db['Field'].'\');  ?>
</div>';		
				
				
				// if validation
				if($this->input->post($db['Field'].'_validation[]'))
				{
					$rcondition = '';
					$mcondition = '';
					
					foreach($this->input->post($db['Field'].'_validation[]') as $validate)
					{
						
						
						if($validate=='required')
						{
							$rcondition.= 'required : function() { CKEDITOR.instances.'.$db['Field'].'.updateElement(); },';
							
							$mcondition.= 'required : "'.$display.' field is required",';
						}
						if($validate=='num')
						{
							$rcondition.= 'number : true,';
							
							$mcondition.= 'number : "only numeric",';
						}
						if($validate=='minlength')
						{
							$rcondition.= ' minlength: 10,';
							$mcondition.= 'minlength : "minimum length required is 10",';
						}
						if($validate=='maxlength')
						{
							$rcondition.= 'maxlength : 10,';
							$mcondition.= 'maxlength : "maximum length allowed is 10",';
						}
						if($validate=='remote')
						{
							$rcondition.= 'remote : { url:"'.base_url().'welcome/duplicatecheck", type  : "post", data: { field :\''.$db['Field'].'\', table :\''.$table.'\' } },';
							$mcondition.= 'remote : "Already  exists",';
						}
						
						
						

						
					}

					$validaterules.= $db['Field'].'	: { '.$rcondition.' },'; 
					
					$validatemessage.= $db['Field'].'	: { '.$mcondition.' },';
				}
				
				
				
				$textareascript='<script>
		var editor = CKEDITOR.replace( \''.$db['Field'].'\',{
		//uiColor: \'#14B8C4\',
		//language: \'en\'
		//magicline_color: \'#14B8C4\',
		//fullPage: true,
		//allowedContent: \'p\'
		allowedContent: \'h1 h2 h3 p blockquote strong em;\' + \'a[!href];\' + \'img(left,right)[!src,alt,width,height];\' + \'table tr th td caption;\' + \'span{!font-family};\' + \'span{!color};\' + \'span(!marker);\' + \'del ins\'
		
	});</script>';
				 
			}
			
			
			// if input type Multiselect 
			if($this->input->post($db['Field'].'_type')=='multiselect')
			{
				if($this->input->post('from'.$table.$db['Field'].'_type')=='manual')
				{
					$selectcreate = '
	<select class="select2" multiple="multiple" name="'.$db['Field'].'[]" id="'.$db['Field'].'"  >
	<option value="">------select------</option>';		
					$count = count($this->input->post('manualkey'.$table.$db['Field'].'_type[]'));
					for($i=0; $i<$count; $i++)
					{
						$selectcreate.= '<option value="'.$this->input->post('manualkey'.$table.$db['Field'].'_type[]')[$i].'" <?php if($this->uri->segment(2)=="edit" && '.$this->input->post('manualkey'.$table.$db['Field'].'_type[]')[$i].'==$edit[\''.$db['Field'].'\']) { echo "selected"; } else { echo set_value(\''.$db['Field'].'[]\'); } ?> >'.$this->input->post('manualvalue'.$table.$db['Field'].'_type[]')[$i].'</option>';
					}
					
					$selectcreate.= '<?php echo form_error(\''.$db['Field'].'[]\');  ?><select>';
	
	
					$selectcontentscript = '<script> $(\'#'.$db['Field'].'\').select2(); </script>';
	
					
				$view_content_add.= '
<div class="form-group">
   <label for="'.$display.'">'.$display.'</label><br>
	      '.$selectcreate.'
</div>';		
				// if validation
				if($this->input->post($db['Field'].'_validation[]'))
				{
					$rcondition = '';
					$mcondition = '';
					
					foreach($this->input->post($db['Field'].'_validation[]') as $validate)
					{
						
						
						if($validate=='required')
						{
							$rcondition.= 'required : true,';
							
							$mcondition.= 'required : "'.$display.' field is required",';
						}
						if($validate=='num')
						{
							$rcondition.= 'number : true,';
							
							$mcondition.= 'number : "only numeric",';
						}
						if($validate=='minlength')
						{
							$rcondition.= ' minlength: 10,';
							$mcondition.= 'minlength : "minimum length required is 10",';
						}
						if($validate=='maxlength')
						{
							$rcondition.= 'maxlength : 10,';
							$mcondition.= 'maxlength : "maximum length allowed is 10",';
						}
						if($validate=='remote')
						{
							$rcondition.= 'remote : { url:"'.base_url().'welcome/duplicatecheck", type  : "post", data: { field :\''.$db['Field'].'\', table :\''.$table.'\' } },';
							$mcondition.= 'remote : "Already  exists",';
						}
						
						
						

						
					}

					$validaterules.= '"'.$db['Field'].'[]"	: { '.$rcondition.' },'; 
					
					$validatemessage.= '"'.$db['Field'].'[]"	: { '.$mcondition.' },';
				}
				
			
					

				}

				
				if($this->input->post('from'.$table.$db['Field'].'_type')=='table')
				{
					$tablename = $this->input->post('tablefrom'.$table.$db['Field'].'_type');
					
					$selectdisplay = $this->input->post('fieldtablefrom'.$table.$db['Field'].'_type');
					
					$selectvalue = $this->db->query("SHOW COLUMNS FROM ".$tablename." where extra like '%auto_increment%'")->row_array()['Field'];
					
	$selectcreate = '
	<select class="select2" multiple="multiple" name="'.$db['Field'].'[]" id="'.$db['Field'].'"  >
	<option value="">------select------</option>';				  
	
	$selectcreate.= '
<?php 
		
	foreach($this->db->select(\''.$selectvalue.','.$selectdisplay.'\')->from(\''.$tablename.'\')->where(\'status\',\'Active\')->get()->result_array() as $selectcreate)
	{
		
		$split = explode(\',\',$edit[\''.$db['Field'].'\']);
			
		if($this->uri->segment(2)=="edit" && in_array($selectcreate[\''.$selectdisplay.'\'], $split))
		{
			$select = "selected";
		}
		else {
			$select = "";
		}
		echo "<option value=\'".$selectcreate[\''.$selectdisplay.'\']."\' ".$select." >".$selectcreate[\''.$selectdisplay.'\']."</option>";
	}
?>';
	$selectcreate.= '<?php echo form_error(\''.$db['Field'].'[]\');  ?><select>';
	
	
	$selectcontentscript = '<script> $(\'#'.$db['Field'].'\').select2(); </script>';
	
					
				$view_content_add.= '
<div class="form-group">
   <label for="'.$display.'">'.$display.'</label><br>
	      '.$selectcreate.'
</div>';		
				// if validation
				if($this->input->post($db['Field'].'_validation[]'))
				{
					$rcondition = '';
					$mcondition = '';
					
					foreach($this->input->post($db['Field'].'_validation[]') as $validate)
					{
						
						
						if($validate=='required')
						{
							$rcondition.= 'required : true,';
							
							$mcondition.= 'required : "'.$display.' field is required",';
						}
						if($validate=='num')
						{
							$rcondition.= 'number : true,';
							
							$mcondition.= 'number : "only numeric",';
						}
						if($validate=='minlength')
						{
							$rcondition.= ' minlength: 10,';
							$mcondition.= 'minlength : "minimum length required is 10",';
						}
						if($validate=='maxlength')
						{
							$rcondition.= 'maxlength : 10,';
							$mcondition.= 'maxlength : "maximum length allowed is 10",';
						}
						if($validate=='remote')
						{
							$rcondition.= 'remote : { url:"'.base_url().'welcome/duplicatecheck", type  : "post", data: { field :\''.$db['Field'].'\', table :\''.$table.'\' } },';
							$mcondition.= 'remote : "Already  exists",';
						}
						
						
						

						
					}

					$validaterules.= '"'.$db['Field'].'[]"	: { '.$rcondition.' },'; 
					
					$validatemessage.= '"'.$db['Field'].'[]"	: { '.$mcondition.' },';
				}
				 
			}
			
		}
			
						
			
			// if input type Select 
			if($this->input->post($db['Field'].'_type')=='select')
			{
				if($this->input->post('from'.$table.$db['Field'].'_type')=='manual')
				{
					$selectcreate = '
	<select class="select2" name="'.$db['Field'].'" id="'.$db['Field'].'"  >
	<option value="">------select------</option>';		
					$count = count($this->input->post('manualkey'.$table.$db['Field'].'_type[]'));
					for($i=0; $i<$count; $i++)
					{
						$selectcreate.= '<option value="'.$this->input->post('manualkey'.$table.$db['Field'].'_type[]')[$i].'" <?php if($this->uri->segment(2)=="edit" && '.$this->input->post('manualkey'.$table.$db['Field'].'_type[]')[$i].'==$edit[\''.$db['Field'].'\']) { echo "selected"; } else { echo set_value(\''.$db['Field'].'[]\'); } ?> >'.$this->input->post('manualvalue'.$table.$db['Field'].'_type[]')[$i].'</option>';
					}
					
					$selectcreate.= '<?php echo form_error(\''.$db['Field'].'[]\');  ?><select>';
	
	
					$selectcontentscripts = '<script> $(\'#'.$db['Field'].'\').select2(); </script>';
	
					
				$view_content_add.= '
<div class="form-group">
   <label for="'.$display.'">'.$display.'</label><br>
	      '.$selectcreate.'
</div>';		
				// if validation
				if($this->input->post($db['Field'].'_validation[]'))
				{
					$rcondition = '';
					$mcondition = '';
					
					foreach($this->input->post($db['Field'].'_validation[]') as $validate)
					{
						
						
						if($validate=='required')
						{
							$rcondition.= 'required : true,';
							
							$mcondition.= 'required : "'.$display.' field is required",';
						}
						if($validate=='num')
						{
							$rcondition.= 'number : true,';
							
							$mcondition.= 'number : "only numeric",';
						}
						if($validate=='minlength')
						{
							$rcondition.= ' minlength: 10,';
							$mcondition.= 'minlength : "minimum length required is 10",';
						}
						if($validate=='maxlength')
						{
							$rcondition.= 'maxlength : 10,';
							$mcondition.= 'maxlength : "maximum length allowed is 10",';
						}
						if($validate=='remote')
						{
							$rcondition.= 'remote : { url:"'.base_url().'welcome/duplicatecheck", type  : "post", data: { field :\''.$db['Field'].'\', table :\''.$table.'\' } },';
							$mcondition.= 'remote : "Already  exists",';
						}
						
						
						

						
					}

					$validaterules.= $db['Field'].'	: { '.$rcondition.' },'; 
					
					$validatemessage.= $db['Field'].'	: { '.$mcondition.' },';
				}
				
			
					

				}

				
				if($this->input->post('from'.$table.$db['Field'].'_type')=='table')
				{
					$tablename = $this->input->post('tablefrom'.$table.$db['Field'].'_type');
					
					$selectdisplay = $this->input->post('fieldtablefrom'.$table.$db['Field'].'_type');
					
					$selectvalue = $this->db->query("SHOW COLUMNS FROM ".$tablename." where extra like '%auto_increment%'")->row_array()['Field'];
					
	$selectcreate = '
	<select class="select2" name="'.$db['Field'].'" id="'.$db['Field'].'"  >
	<option value="">------select------</option>';				  
	
	$selectcreate.= '
<?php 		
	foreach($this->db->select(\''.$selectvalue.','.$selectdisplay.'\')->from(\''.$tablename.'\')->where(\'status\',\'Active\')->get()->result_array() as $selectcreate)
	{
			
		if($this->uri->segment(2)=="edit" && $selectcreate[\''.$selectdisplay.'\']==$edit[\''.$db['Field'].'\'])
		{
			$select = "selected";
		}
		else {
			$select = "";
		}
		echo "<option value=\'".$selectcreate[\''.$selectdisplay.'\']."\' ".$select." >".$selectcreate[\''.$selectdisplay.'\']."</option>";
	}
?>';
	$selectcreate.= '<?php echo form_error(\''.$db['Field'].'[]\');  ?><select>';
	
	
	$selectcontentscripts = '<script> $(\'#'.$db['Field'].'\').select2(); </script>';
	
					
				$view_content_add.= '
<div class="form-group">
   <label for="'.$display.'">'.$display.'</label><br>
	      '.$selectcreate.'
</div>';		
				// if validation
				if($this->input->post($db['Field'].'_validation[]'))
				{
					$rcondition = '';
					$mcondition = '';
					
					foreach($this->input->post($db['Field'].'_validation[]') as $validate)
					{
						
						
						if($validate=='required')
						{
							$rcondition.= 'required : true,';
							
							$mcondition.= 'required : "'.$display.' field is required",';
						}
						if($validate=='num')
						{
							$rcondition.= 'number : true,';
							
							$mcondition.= 'number : "only numeric",';
						}
						if($validate=='minlength')
						{
							$rcondition.= ' minlength: 10,';
							$mcondition.= 'minlength : "minimum length required is 10",';
						}
						if($validate=='maxlength')
						{
							$rcondition.= 'maxlength : 10,';
							$mcondition.= 'maxlength : "maximum length allowed is 10",';
						}
						if($validate=='remote')
						{
							$rcondition.= 'remote : { url:"'.base_url().'welcome/duplicatecheck", type  : "post", data: { field :\''.$db['Field'].'\', table :\''.$table.'\' } },';
							$mcondition.= 'remote : "Already  exists",';
						}
						
						
						

						
					}

					$validaterules.= $db['Field'].'	: { '.$rcondition.' },'; 
					
					$validatemessage.= $db['Field'].'	: { '.$mcondition.' },';
				}
				 
			}
			
		}
			
			// if input type date
			if($this->input->post($db['Field'].'_type')=='date')
			{
				$view_content_add.= '
<div class="form-group">
   <label for="'.$display.'">'.$display.'</label>
	      <input type="text" autocomplete="off" name="'.$db['Field'].'" id="'.$db['Field'].'" class="form-control" value="<?php if($this->uri->segment(3)) { echo $edit[\''.$db['Field'].'\']; } else { echo set_value(\''.$db['Field'].'\'); } ?>" >
<?php echo form_error(\''.$db['Field'].'\');  ?>
	      </div>';		
				
				$datescript = '<script>
  $( function() {
  	
	$( "#'.$db['Field'].'" ).keypress( function(e) { e.preventDefault(); });
	
    $( "#'.$db['Field'].'" ).datepicker({dateFormat: \'yy/mm/dd\'});
  } );
  </script>';
				
				// if validation
				if($this->input->post($db['Field'].'_validation[]'))
				{
					$rcondition = '';
					$mcondition = '';
					
					foreach($this->input->post($db['Field'].'_validation[]') as $validate)
					{
						
						
						if($validate=='required')
						{
							$rcondition.= 'required : true,';
							
							$mcondition.= 'required : "'.$display.' field is required",';
						}
						if($validate=='num')
						{
							$rcondition.= 'number : true,';
							
							$mcondition.= 'number : "only numeric",';
						}
						if($validate=='minlength')
						{
							$rcondition.= ' minlength: 10,';
							$mcondition.= 'minlength : "minimum length required is 10",';
						}
						if($validate=='maxlength')
						{
							$rcondition.= 'maxlength : 10,';
							$mcondition.= 'maxlength : "maximum length allowed is 10",';
						}
						if($validate=='remote')
						{
							$rcondition.= 'remote : { url:"'.base_url().'welcome/duplicatecheck", type  : "post", data: { field :\''.$db['Field'].'\', table :\''.$table.'\' } },';
							$mcondition.= 'remote : "Already  exists",';
						}
						
						
						

						
					}

					$validaterules.= $db['Field'].'	: { '.$rcondition.' },'; 
					
					$validatemessage.= $db['Field'].'	: { '.$mcondition.' },';
				}
				 
			}
			
			
			// if input type radio
			if($this->input->post($db['Field'].'_type')=='radio')
			{
				
				if($this->input->post('check'.$table.$db['Field'].'_type'))
				{
					//echo $this->input->post('check'.$table.$db['Field'].'_type'); 
					
					
					$checkseperate = explode(",",$this->input->post('check'.$table.$db['Field'].'_type'));
					
					$checkboxcreate = '
					'.$display.'
				
					
					';
					
					foreach ($checkseperate as $ck) {
						

						
						$incheckseperate = explode('=>', $ck);
						
						$checkboxcreate.= '<br /><input name="'.$db['Field'].'[]" id="'.$db['Field'].'[]" type="radio" value="'.$incheckseperate[1].'" <?php if($this->uri->segment(2)==\'edit\') { $split=explode(\',\',$edit[\''.$db['Field'].'\']);   if(in_array("'.$incheckseperate[1].'", $split)) { echo "checked"; } else { echo ""; } } ?> ><label for="">'.$incheckseperate[1].'</label><br />';
					
	
					}
					
					$checkboxcreate.= '
			<?php echo form_error(\''.$db['Field'].'[]\');  ?></section>';
						
						$view_content_add.= $checkboxcreate;
						
				}
					
				
				
				// if validation
				if($this->input->post($db['Field'].'_validation[]'))
				{
					$rcondition = '';
					$mcondition = '';
					
					foreach($this->input->post($db['Field'].'_validation[]') as $validate)
					{
						
						
						if($validate=='required')
						{
							$rcondition.= 'required : true,';
							
							$mcondition.= 'required : "'.$display.' field is required",';
						}
						if($validate=='num')
						{
							$rcondition.= 'number : true,';
							
							$mcondition.= 'number : "only numeric",';
						}
						if($validate=='minlength')
						{
							$rcondition.= ' minlength: 10,';
							$mcondition.= 'minlength : "minimum length required is 10",';
						}
						if($validate=='maxlength')
						{
							$rcondition.= 'maxlength : 10,';
							$mcondition.= 'maxlength : "maximum length allowed is 10",';
						}
						if($validate=='remote')
						{
							$rcondition.= 'remote : { url:"'.base_url().'welcome/duplicatecheck", type  : "post", data: { field :\''.$db['Field'].'\', table :\''.$table.'\' } },';
							$mcondition.= 'remote : "Already  exists",';
						}
						
						
						

						
					}

					$validaterules.= '"'.$db['Field'].'[]"	: { '.$rcondition.' },'; 
					
					$validatemessage.= '"'.$db['Field'].'[]"	: { '.$mcondition.' },';
				}
				 
			}
			
			
			
			// if input type check box
			if($this->input->post($db['Field'].'_type')=='checkbox')
			{
				
				if($this->input->post('check'.$table.$db['Field'].'_type'))
				{
					//echo $this->input->post('check'.$table.$db['Field'].'_type'); 
					
					
					$checkseperate = explode(",",$this->input->post('check'.$table.$db['Field'].'_type'));
					
					$checkboxcreate = '
					'.$display.'
				';
					
					foreach ($checkseperate as $ck) {
						

						
						$incheckseperate = explode('=>', $ck);
						
						$checkboxcreate.= '<br /><input name="'.$db['Field'].'[]" id="'.$db['Field'].'[]" type="checkbox" value="'.$incheckseperate[1].'" <?php if($this->uri->segment(2)==\'edit\') { $split=explode(\',\',$edit[\''.$db['Field'].'\']);   if(in_array("'.$incheckseperate[1].'", $split)) { echo "checked"; } else { echo ""; } } ?> ><label for="">'.$incheckseperate[1].'</label><br />';
					
	
					}
					
					$checkboxcreate.= '<br />
			<?php echo form_error(\''.$db['Field'].'[]\');  ?></section>';
						
						$view_content_add.= $checkboxcreate;
					
					
				}
				
				// if validation
				if($this->input->post($db['Field'].'_validation[]'))
				{
					$rcondition = '';
					$mcondition = '';
					
					foreach($this->input->post($db['Field'].'_validation[]') as $validate)
					{
						
						
						if($validate=='required')
						{
							$rcondition.= 'required : true,';
							
							$mcondition.= 'required : "'.$display.' field is required",';
						}
						if($validate=='num')
						{
							$rcondition.= 'number : true,';
							
							$mcondition.= 'number : "only numeric",';
						}
						if($validate=='minlength')
						{
							$rcondition.= ' minlength: 10,';
							$mcondition.= 'minlength : "minimum length required is 10",';
						}
						if($validate=='maxlength')
						{
							$rcondition.= 'maxlength : 10,';
							$mcondition.= 'maxlength : "maximum length allowed is 10",';
						}
						if($validate=='remote')
						{
							$rcondition.= 'remote : { url:"'.base_url().'welcome/duplicatecheck", type  : "post", data: { field :\''.$db['Field'].'\', table :\''.$table.'\' } },';
							$mcondition.= 'remote : "Already  exists",';
						}
						
						
						

						
					}

					$validaterules.= '"'.$db['Field'].'[]"	: { '.$rcondition.' },'; 
					
					$validatemessage.= '"'.$db['Field'].'[]"	: { '.$mcondition.' },';
				}
				 
			}
				
				
				
				
	
			
		}
	
	}
} // add form end

// Script validations

$view_content_add.= '<button type="submit" class="btn btn-default"><?php if($this->uri->segment(2)=="edit") { echo "Update"; } else { echo "Save"; } ?></button>
  	</form>
  </div>
 </div>';

$view_content_script = '';

if(isset($selectcontentscript) && $selectcontentscript!='')
{
	$view_content_script.= $selectcontentscript;
}
if(isset($selectcontentscripts) && $selectcontentscripts!='')
{
	$view_content_script.= $selectcontentscripts;
}
 
if(isset($datescript) && $datescript!='')
{
	$view_content_script.= $datescript;
}
if(isset($textareascript) && $textareascript!='')
{
	$view_content_script.= $textareascript;
}


$view_content_script.= '<script type="text/javascript">

	
$("#'.$table.'").validate({
				
				ignore: [],
				rules: {
					'.$validaterules.'

				},
				messages: {
					'.$validatemessage.'

				},
				errorPlacement: function(error, element) {
					  
					
					  
					  if (element.parent(\'.input-group\').length) { 
					        error.insertAfter(element.parent());      
					    } else if (element.hasClass(\'select2\')) {     
					        error.insertAfter(element.next(\'span\'));  
					    } else {                                      
					        error.insertAfter(element);               
					    }
					   
					},
				
			});
				
				
 alertify.defaults = {
    // dialogs defaults
    autoReset:true,
    basic:false,
    closable:true,
    closableByDimmer:true,
    frameless:false,
    maintainFocus:true, // <== global default not per instance, applies to all dialogs
    maximizable:true,
    modal:true,
    movable:true,
    moveBounded:false,
    overflow:true,
    padding: true,
    pinnable:true,
    pinned:true,
    preventBodyShift:false, // <== global default not per instance, applies to all dialogs
    resizable:true,
    startMaximized:false,
    transition:\'pulse\',

    // notifier defaults
    notifier:{
        // auto-dismiss wait time (in seconds)  
        delay:5,
        // default position
        position:\'bottom-right\'
    },

    // language resources 
    glossary:{
        // dialogs default title
        title:\'Message\',
        // ok button text
        ok: \'OK\',
        // cancel button text
        cancel: \'Cancel\'            
    },

    // theme settings
    theme:{
        // class name attached to prompt dialog input textbox.
        input:\'ajs-input\',
        // class name attached to ok button
        ok:\'ajs-ok\',
        // class name attached to cancel button 
        cancel:\'ajs-cancel\'
    }
};  

function status(id,status) {
	
	if(status==\'Active\')
	{
		stat = \'Inactive\';
	}
	if(status==\'Inactive\')
	{
		stat = \'Active\';
	}
	
	
	$.ajax({
	        url: "<?php echo base_url(); ?>'.$table.'/statuschange",
	        type : "POST",
	        data: {id : id,status : stat},
	        success: function (data) {
     			
     			//alertify.alert("Status changed successfully", function(){
		   			
		   		location.reload();
		    
 				//});
			},
			      
		});
	
	
	
}
		

function deleteid(id) {
       
    alertify.confirm("Are you sure to delete.?", function (e) {
       
	    var status = \'Delete\';
	   
	   $.ajax({
	        url: "<?php echo base_url(); ?>'.$table.'/statuschange",
	        type : "POST",
	        data: {id : id,status : status},
	        success: function (data) {
     			
     		//	alertify.alert("Deleted successfully", function(){
		   		location.reload();
		    
 			//	});
			},
			      
		});
				   
		//alertify.alert("Deleted successfully");	
			     
				 
				           
	}, function() {
		
			    	
	}).setting(\'labels\',{\'ok\':\'Delete\', \'cancel\': \'Cancel\'});
            
}
	
	$(document).on(\'click\', \'#select_all\', function(){
	//$(\'#select_all\').on(\'click\', function(e) {
		if($(this).is(\':checked\',true))  
		{
			$(".sub_chk").prop(\'checked\', true);  
		}  
		else  
		{  
			$(".sub_chk").prop(\'checked\',false);  
		}  
	});
	$(\'.delete_all\').on(\'click\', function(e) { 
		var allVals = [];  
		$(".sub_chk:checked").each(function() {  
			allVals.push($(this).val());
		});  
		//alert(allVals.length); return false;  
		if(allVals.length <=0)  
		{  
			alertify.alert("Please select atleast one record to delete.");	
		}  
		else {
			
			alertify.confirm("Are you sure to delete.?", function (e) {
       
	   var ids = allVals.join(",");
	    
	   var status = \'Delete\';
	   
	   $.ajax({
	        url: "<?php echo base_url(); ?>'.$table.'/statuschangeall",
	        type : "POST",
	        data: {ids : ids,status : status},
	        success: function (data) {
     			
     			alertify.alert("Deleted successfully", function(){
		   		location.reload();
		    
 				});
			},
			      
		});
				   
		//alertify.alert("Deleted successfully");	
			     
				 
				           
	}, function() {
		
			    	
	}).setting(\'labels\',{\'ok\':\'Delete\', \'cancel\': \'Cancel\'});
			
				  
			
              
				

			 
		}  
	});
	
	
	$(\'.active_all\').on(\'click\', function(e) { 
		var allVals = [];  
		$(".sub_chk:checked").each(function() {  
			allVals.push($(this).val());
		});  
		//alert(allVals.length); return false;  
		if(allVals.length <=0)  
		{  
			alertify.alert("Please select atleast one record to delete.");	
		}  
		else {
			
			alertify.confirm("Are you sure to Active all.?", function (e) {
       
	   var ids = allVals.join(","); 
	   
	   var status = \'Active\';
	   
	   $.ajax({
	        url: "<?php echo base_url(); ?>'.$table.'/statuschangeall",
	        type : "POST",
	        data: {ids : ids ,status : status},
	        success: function (data) {
     			
     			//alertify.alert("Deleted successfully", function(){
		   		location.reload();
		    
 				//});
			},
			      
		});
				   
		//alertify.alert("Deleted successfully");	
			     
				 
				           
	}, function() {
		
			    	
	}).setting(\'labels\',{\'ok\':\'Yes\', \'cancel\': \'No\'});
			
				  
			
              
				

			 
		}  
	});
	
	
	
	$(\'.inactive_all\').on(\'click\', function(e) { 
		var allVals = [];  
		$(".sub_chk:checked").each(function() {  
			allVals.push($(this).val());
		});  
		//alert(allVals.length); return false;  
		if(allVals.length <=0)  
		{  
			alertify.alert("Please select atleast one record.");	
		}  
		else {
			
			alertify.confirm("Are you sure to Inactive all.?", function (e) {
       
	   var ids = allVals.join(","); 
	   
	   var status = \'Inactive\';
	   
	   $.ajax({
	        url: "<?php echo base_url(); ?>'.$table.'/statuschangeall",
	        type : "POST",
	        data: {ids : ids ,status : status},
	        success: function (data) {
     			
     			//alertify.alert("Deleted successfully", function(){
		   		location.reload();
		    
 				//});
			},
			      
		});
				   
		//alertify.alert("Deleted successfully");	
			     
				 
				           
	}, function() {
		
			    	
	}).setting(\'labels\',{\'ok\':\'Yes\', \'cancel\': \'No\'});
			
				  
			
              
				

			 
		}  
	});
	
	
	
	
	
	
</script>

';


// $htmlcontent.=$view_content_list;
// 
// $htmlcontent.=$view_content_add;
// 
// $htmlcontent.=$view_content_script;
// 
// $htmlcontent.='';
			
// view file ends		
	$template = file_get_contents(APPPATH.'/views/template.php');	
	
	
	$replacelist = str_replace("##list##", $view_content_list ,$template);
	
	$replacelist = str_replace("##add##", $view_content_add ,$replacelist);
	
	$htmlcontent = str_replace("##script##", $view_content_script ,$replacelist);
	//creating view file
		
	if(file_exists(APPPATH.'/views/'.$table.'.php'))
	{
	 	unlink(APPPATH.'/views/'.$table.'.php');
	}
	 
	if(file_put_contents(APPPATH.'/views/'.$table.'.php', $htmlcontent))
	{
	 	chmod(APPPATH.'/views/'.$table.'.php',0777);
	}

		
	//creating controller file
	$post = $this->input->post();
	
 	$controller_content = $this->common->controller($table,$id,$post,$jdata,$bdata,$field);
	
	
	
	
	if(file_exists(APPPATH.'/controllers/'.ucfirst($table).'.php'))
 	{
 		unlink(APPPATH.'/controllers/'.ucfirst($table).'.php');
 	}
 
 	if(file_put_contents(APPPATH.'/controllers/'.ucfirst($table).'.php', $controller_content))
 	{
 		chmod(APPPATH.'/controllers/'.ucfirst($table).'.php',0777);
 	}
	
	redirect($table);	

	// end
	}
	
	
	public function duplicatecheck()
	{
		$table = $this->input->post('table');
		$field = $this->input->post('field');
		$check = $this->input->post($field);
		
		  $where = ''.$field.'=\''.$check.'\' AND status=\'Active\'';
  		  
		
		$num = $this->db->select($field)->from($table)->where($where)->get()->num_rows();
		
		if($num > 0)
		{
			echo "false";
		}
		else {
			echo "true";
		}
		
	}
	
	public function gettable()
	{
		$id = $this->input->post('value');
		$content ='';
		foreach($this->db->query("SHOW COLUMNS FROM ".$id."")->result_array() as $fieldname => $db)
		    {
		    	
				if($db['Extra']!='auto_increment')
				{
					$content.= '<option value="'.$db['Field'].'">'.$db['Field'].'</option>';
				
				}	
				
			}
		echo $content;
		
	}
	
	public function createfile()
	{
		
		 $tables=$this->db->query("Show Tables")->result_array();    
		 
		 $tablename = array();
		 
		 foreach($tables as $key => $val) 
		 {
		 	$tablename[$val['Tables_in_new']] = '';
			
			
			
			foreach($this->db->query("SHOW COLUMNS FROM ".$val['Tables_in_new']."")->result_array() as $fieldname => $db_field_type)
		    {
	    		
				 $tablename[$val['Tables_in_new']][$fieldname] = $db_field_type;
				
		    }
			
			
			
		 }
		 //echo "<pre>"; print_r($tablename); exit;
		 
		 
		 $tables=$this->db->query("Show Tables")->result_array();    
		 
		 $tableselect = '';
		 
		 foreach($tables as $key => $val) 
		 {
		 	$tableselect.= '<option value="'.$val['Tables_in_new'].'" >'.$val['Tables_in_new'].'</option>';
		 }
		 
		 $data['tableselect'] = $tableselect;
		
		 $data['detail'] = $tablename;
		
		 $this->load->view('createfile',$data);
	}
	
	
	public function getloadresults()
	{
	
	  if($this->input->post('getresult'))
	  {
	    
	    $no = $this->input->post('getresult');
	    $select = $this->db->query("select name from img_us_states limit $no,10");
	    //$select = mysql_query("select comment from sample_comment limit $no,10");
	    $data = $select->result();
	    
	    foreach($data as $row)
	    {
	      echo "<p class='rows'>".$row->name."</p>";
	    }
	    
	    exit();
	  }
  
	}
	
	
	public function lazyload()
	{
		$select = $this->db->query("select name from img_us_states limit 0,10");
	    
	    $data['data'] = $select->result();
			
		$this->load->view('lazyload',$data);
	}
	
	
	
	public function alertify()
	{
		$this->load->view('alertify');
	}
	public function slideshowjs()
	{
		$this->load->view('slideshowjs');
	}
	public function avgrnd()
	{
		$this->load->view('avgrnd');
	}
	
	public function jquerygrid()
	{
		$this->load->view('jquerygrid');
	}
	
	public function loadjs()
	{
		$this->load->view('loadjs');
	}
	
	
	public function urisegment()
	{
		
			
		$this->load->view('urisegment');
	}
	
	public function csvview()
	{
		$this->load->view('csvview');
	}
	
	public function xmltocsv()
	{
		
			
		$this->load->view('xmltocsv');
	}
	
	public function csrf_redirect()
	{
	    $flash = 'Please try again..!';
	    $this->session->set_flashdata('message', $flash);
	    redirect('welcome/urisegment');
	}
	
	 
	public function helper()
	{
		
		$val = array(
		        'word'          => rand(1000, 10000),
		        'img_path'      => './captcha/',
		        'img_url'       => base_url().'captcha/',
		        'font_path'     => 'arial.ttf',
		        'img_width'     => '200',
		        'img_height'    => 50,
		        'expiration'    => 720,
		        'word_length'   => 7,
		        'font_size'     => 20,
		        'img_id'        => 'Imageid',
		        'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
		
		        
		        'colors'        => array(
		                'background' => array(255, 255, 255),
		                'border' => array(255, 255, 255),
		                'text' => array(0, 0, 0),
		                'grid' => array(255, 40, 40)
		        )
				// background : White 
		        // border: White 
				//Text: black text and red grid
		);
		
		$captcha = create_captcha($val);
		echo $captcha['image'];
		
		//print_r($captcha);
		
		//exit;
		
		
		  

          $image_array = get_clickable_smileys(base_url().'smileys/', 'comments');
          $col_array = $this->table->make_columns($image_array, 8);

          $data['smiley_table'] = $this->table->generate($col_array);

			//echo "<pre>"; print_r($data['smiley_table']); exit;               

		
		
		$this->load->view('helper',$data);
	}
	
	
	
	 
	 public function makepdf()
	{
		$this->load->view('makepdf');
	}
	
	public function dompdf()
	{
		$this->load->view('dompdf');
	}
	 
	public function imagecrop()
	{
		$this->load->view('imagecrop');
	}
	
	public function openimage()
	{
		
		
		exit;	
	}
	
	
	public function crop()
	{
		//echo "<pre>"; print_r($this->input->post());
		
		$targ_w = $targ_h = 150;
		
		$jpeg_quality = 90;
		
		//$src = base_url().'js/pool.jpg';
		
		$src = $this->input->post('imageurl');
		
		//echo $src;
		
		//exit;
		
		$img_r = imagecreatefromjpeg($src);
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		
		imagecopyresampled($dst_r,$img_r,0,0,$this->input->post('x'),$this->input->post('y'),
		    $targ_w,$targ_h,$this->input->post('w'),$this->input->post('h'));
		
		//header('Content-type: image/jpeg');
		
		//imagejpeg($dst_r, null, $jpeg_quality);
		
		$output_filename = 'get'.time();
		
		imagejpeg($dst_r, 'upload/' . $output_filename .'.jpg');
		
		echo $output_filename.'.jpg';
		
		exit;		
		
		//redirect('welcome/imagecrop');
	}
	
	
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	
	public function tableexport()
	{
		$this->load->view('tableexport');
	}
	
	
	public function selecttodataid()
	{
			parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
			
			if(isset($_GET['q']))
			{
				$where = $_GET['q'];
				
				$query = "SELECT distinct(country_id) as ids FROM `img_us_states`where country_id like '".$where."%' ORDER BY `id` ASC ";
				
			}
			else {
			
				
				$query = "SELECT distinct(country_id) as ids FROM `img_us_states` ORDER BY `country_id` ASC ";
					
			}
			
			$get = $this->db->query($query);
			
			
			$datas = [];
			
			$num = $get->num_rows();
			
			foreach($get->result() as $data)
			{
				$datas[] = array("id" => $data->ids, "text" => $data->ids);
			}
			
			//echo "<pre>"; print_r($totaldata);
			
			//exit;
			
			
			
			
			$json_data = array(
			                "total_count"   => $num,
			                "items"    		=> $datas,
			                //"page"    	=> 3,
			                
			            );
			echo json_encode($json_data);
						
			
	}
	
	
	public function selecttodata()
	{
			parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
			
			if(isset($_GET['q']))
			{
				$where = $_GET['q'];
				
				$cond = "";
				
				if(isset($_GET['check']) && $_GET['check']!='')
				{
					$cond = "country_id=".$_GET['check']." and ";
				}
				
				
				$query = "SELECT * FROM `img_us_states`where ".$cond." name like '".$where."%' ORDER BY `name` ASC ";
				
			}
			else {
					
				$cond = "";
				
				if(isset($_GET['check']) && $_GET['check']!='')
				{
					$cond = "where country_id=".$_GET['check']."";
				}
			
				
				
				$query = "SELECT * FROM `img_us_states` ".$cond." ORDER BY `name` ASC ";
					
			}
			
			$get = $this->db->query($query);
			
			
			$datas = [];
			
			$num = $get->num_rows();
			
			foreach($get->result() as $data)
			{
				$datas[] = array("id" => $data->name, "text" => $data->name);
			}
			
			//echo "<pre>"; print_r($totaldata);
			
			//exit;
			
			
			
			
			$json_data = array(
			                "total_count"   => $num,
			                "items"    		=> $datas,
			                //"page"    	=> 3,
			                
			            );
			echo json_encode($json_data);
						
			
	}


	public function getdata()
	{
			parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
			
			$segments = $this->uri->segment_array();
			
			
			//echo "<pre>"; print_r($segments);
			
			
			$search = array_search('search',$segments);	
	        
	        if($search !==FALSE && $this->uri->segment($search+1)!='')
	        {
	            $search_value = $this->uri->segment($search+1); 
	        }
	        $country = array_search('country',$segments);	
	        
	        if($country !==FALSE && $this->uri->segment($country+1)!='')
	        {
	            $country_value = $this->uri->segment($country+1); 
	        }
			
			
			
			
			$order = $_GET['order'];
			
			$limit = $_GET['limit'];
			
			$offset = $_GET['offset'];
			
		
			$cond="";
			
			if(isset($search_value)){
				
				$search = $search_value;
				
				$cond="where name like '".$search."%' or id like '".$search."%'";
				
			}
			
			$cond1="";
			
			if(isset($order))
			{
					$sort = "name";
					
				if(isset($_GET['sort']))
				{
					$sort = $_GET['sort'];
				}
				
				
				$cond1="ORDER BY ".$sort." ".$order."";
			}
			
			$cond2="limit ".$limit." offset ".$offset."";
			
			
			
			// num rows total
				
			
			$querys = "SELECT * FROM `img_us_states` ";
			
			$gets = $this->db->query($querys);
				
			$data = $gets->result();
			
			$num = $gets->num_rows();
			
			// limit records 
			
			$querys.= " ".$cond1."  ".$cond2."";
			
			$get = $this->db->query($querys);
			
			
			// filter records
			
			
			if(isset($search_value) && $search_value!=''){
			
			$query = "SELECT * FROM `img_us_states` ".$cond." ".$cond1." ";
			
			
			$get = $this->db->query($query);
			
			$num = $get->num_rows();
			
			
			$query.="  ".$cond2."";
			
			
			$get = $this->db->query($query);
			
			
			}
			
			
			
		
		
		
		//echo $query; exit;
		
		
		
		
		$datas = [];
		
		
		
		
		foreach($get->result() as $data)
		{
			
			
			
			$action = "<button type='button' onclick='edit(".$data->id.")'; class='btn btn-primary edit'>Edit</button> / <button type='button' onclick='deleteid(".$data->id.")'; class='btn btn-danger delete'>Delete</button>";
			
			$datas[] = array("id" => $data->id, "name" => $data->name, "action" => $action);
			
		}
		
		//echo "<pre>"; print_r($totaldata);
		
		//exit;
		
		
		
		
		$json_data = array(
		                "total"   => $num,
		                "rows"    		=> $datas,
		                //"page"    	=> 3,
		                
		            );
		echo json_encode($json_data);
		
		exit;
		
	}

	public function validate()
	{
		
		$this->load->view('validate');
		
	}

	public function checkdata()
	{
		
		
			//parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
			
				$get = $this->input->post('statename');
				
				$query = "SELECT * FROM `img_us_states`where name='".$get."'";
			
				$data = $this->db->query($query);
				
				$numrows = $data->num_rows();
				
				
				
				if($numrows > 0)
				{
					echo "false";
				}
				else {
					echo "true";
				}
				
			
						
			
	}
	
	
	
	
	
}