 <?php
	include_once('../../connection/sschecker.php');
	include_once('../../connection/connect_db.php');
	
	$table='material.authorise';
	$user=$_SESSION['username'];
	$compcode=$_SESSION['company'];
	
	$responce = new stdClass();
	
	/*if(isset($_GET['Scol']) && $_GET['Stext'] == 'authorid'){
		$SQL = "SELECT SELECT SELECT compcode, authorid, name, password, deptcode,
		 CASE WHEN active = 1 THEN 'Yes' ELSE 'No' END AS active,  
		adddate, adduser, upddate,  upduser 
		 FROM $table 
				WHERE {$_GET['Scol']} AND compcode='$compcode'  
			LIKE '{$_GET['Stext']}%' 
			ORDER BY authorid ASC";
			//WHERE recstatus = 'A' AND {$_GET['Scol']}
			
		}
			
	else */if(isset($_GET['Scol']) && $_GET['Stext'] != ''){
/*		$SQL = "SELECT * FROM $table 
				WHERE {$_GET['Scol']}    
			LIKE '{$_GET['Stext']}%' 
			ORDER BY debtortycode ASC";*/
			
		$addSql='';
		$searchcol=$_GET['Scol'];
		$searchStext=$_GET['Stext'];
		
		$parts = explode(' ', $searchStext);
		$partsLength  = count($parts);
		while($partsLength>0){
			$partsLength--;
			$addSql.=" AND {$_GET['Scol']} like '%{$parts[$partsLength]}%' ";
		}
		$SQL = "SELECT compcode, authorid, name, password, deptcode,
		 CASE WHEN active = 1 THEN 'Yes' ELSE 'No' END AS active,  
		adddate, adduser, upddate,  upduser 
		FROM $table 
			WHERE compcode='$compcode'".$addSql.
			"ORDER BY authorid ASC";
	
			//WHERE recstatus = 'A' AND {$_GET['Scol']}
	}else{
		$SQL = "SELECT compcode, authorid, name, password, deptcode,
		 CASE WHEN active = 1 THEN 'Yes' ELSE 'No' END AS active,  
		adddate, adduser, upddate,  upduser 
		FROM $table 
		WHERE compcode='$compcode'
		ORDER BY authorid ASC";
		//SELECT * FROM glmasref WHERE recstatus ='A'  ORDER BY glaccount ASC
	}
	
	$result = $mysqli->query($SQL);
	if (!$result) { echo $mysqli->error; }
    
    
    
    
	$i=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$responce->rows[$i]['id']=$row['authorid'];
		$responce->rows[$i]['cell']=array($row['compcode'],
		$row['authorid'],
		$row['name'],
		$row['password'],
		$row['deptcode'], 
		$row['active'],
		$row['adddate'],
		$row['adduser'],
		$row['upddate'],
		$row['upduser']  
	   
		);
			  
		$i++;
	}
	$result->close();
	
	echo json_encode($responce);
	$mysqli->close();

?>