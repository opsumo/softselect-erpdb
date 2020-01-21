<?php
include_once("../../wp-config.php");

class softselect
{
	private $link;
	
	public function __construct()
	{		
		$this->link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die(mysqli_error($this->link));
//		self::setdb();
	}
	
//	private function setdb()
//	{
//		if(!empty($this->link))
//			mysqli_select_db(DB_NAME,$this->link);
//	}
	
	public function getCompanySize()
	{
		$data = array();
		$i = 0;
				
		if(!empty($this->link))
		{
			$sql = "select cost_range_id, descript from cost_range order by display_order";
			$result = mysqli_query($this->link, $sql);
			while($rows=mysqli_fetch_array($result))
			{
				$data[$i]['cost_range_id'] = $rows['cost_range_id'];
				$data[$i]['descript'] = $rows['descript'];
				$i++;
			}
			mysqli_free_result($result);
		}
		return $data;
	}
	
	public function getIndustries()
	{
		$data = array();
		$i = 0;
		if(!empty($this->link))
		{
			$sql = "SELECT market_id as marketid, market_description as marketdescription 
					FROM target_market where status = 1
					ORDER BY CASE WHEN market_id = -1 
					THEN '' ELSE market_description END";
			$result = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
			while($rows=mysqli_fetch_array($result))
			{
				$data[$i]['marketid'] = $rows['marketid'];
				$data[$i]['marketdescription'] = $rows['marketdescription'];
				$i++;
			}
			mysqli_free_result($result);
		}
		return $data;
	}
	
	public function getMpgEnv()
	{
		$data = array();
		$i = 0;
		if(!empty($this->link))
		{
			$sql = "select mfg_type_id,mfg_type_description from mfg_type ORDER BY case when mfg_type_id = -1 then '' else mfg_type_description end";
			$result = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
			while($rows = mysqli_fetch_array($result))
			{
				$data[$i]['type'] = $rows['mfg_type_id'];
				$data[$i]['type_description'] = $rows['mfg_type_description'];
				$i++;
			}
			mysqli_free_result($result);
		}
		return $data;
	}
	
	public function getPackage($vname="",$pname="")
	{
		$sql = 'select v.vendor_name as vendor_name, p.product_name as product_name, 
                CASE WHEN INSTR(v.www, \'://\') > 0 THEN v.www ELSE CONCAT(\' ://\', v.www) END AS www, 
                product_id as product_id 
				from vendor v join product p on p.vendor_id = v.vendor_id ';

		if(!empty($vname) && empty($pname))
		{
			$sql .= "where v.vendor_name like '%".$vname."%' ";
		}
		if(!empty($pname) && empty($vname))
		{
			$sql .= "where p.product_name like '%".$pname."%' ";
		}
		if(!empty($vname) && !empty($pname))
		{
			$sql .= "where v.vendor_name like '%".$vname."%' or p.product_name like '%".$pname."%' ";
		}
		$sql .= " and v.vendor_id <> 0 and p.product_id <> 0  and v.status = 1 and p.status = 1 order by v.vendor_name, p.product_name";

		$data = array();
		$i = 0;
		if(!empty($this->link))
		{
			$result = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
			while($rows = mysqli_fetch_array($result))
			{
				$data[$i]['vendor_name'] = $rows['vendor_name'];
				$data[$i]['product_name'] = $rows['product_name'];
				$data[$i]['www'] = $rows['www'];
				$data[$i]['product_id'] = $rows['product_id'];
				$i++;
			}
			mysqli_free_result($result);
		}
		return $data;
	}
	
	public function getReport($email,$ip_addr,$comsize,$pri,$sec,$package)
	{
		//self::truncateProducts();
		//self::truncateFocus();
		$data = array();
		$dataheader = array();
		$databody = array();
		$id = '';
		$i = 0;
		$x = 0;
		if(!empty($this->link))
		{

            $sql = "SELECT user_id FROM user WHERE email_address = '$email'";

            $result = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
            $row = mysqli_fetch_array($result);
            $ip = $_SERVER['REMOTE_ADDR'];
            mysqli_free_result($result);
            if ($row) {
                $user_id = $row[0];
                $sql = "UPDATE user SET last_login = NOW(), ip_address = '$ip' WHERE user_id = $user_id";
                mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
            }
            else {
                // Add user if doesn't exist
                $sql = "INSERT INTO user(email_address, password, firm_type, geo_location, 
                                         activation_code, activation_expire_date, register_date, 
                                         last_login, ip_address) 
                        VALUES ('$email','Drip','Drip','Drip',0,'1/1/2000', NOW(), NOW(), '$ip')";

                mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
                $user_id = mysqli_insert_id($this->link);
            }

            // Save query
            $sql = "insert into query (user_id,query_date,ip_address,category_id,cost_range_id,market_id1,market_id2,focus_level1,focus_level2,focus_level3,package_id_string)
					values(".$user_id.",NOW(),'".$ip_addr."',1,".$comsize.",".$pri.",".$sec.",1,0,0,'".$package."')";
			
			$insertresult = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
			
			// this is to get the last insert id
			
			$id = mysqli_insert_id($this->link);
			
			// get the header
			 
			$sql = "SELECT	'c.description' AS CategoryDsc, 
					'c.spc_definition' AS SPCDefinition, 
					'c.spc_instructions' AS SPCInstructions,
					r.descript AS CostRangeDsc,
					q.market_id1,
					m1.market_description AS MarketDsc1,
					q.market_id2,
					m2.market_description AS MarketDsc2,
					q.mfg_type_id1,					
					q.focus_level1,
					q.focus_level2,
					q.focus_level3,
					0 as catCount
					FROM query q
					JOIN cost_range r ON r.cost_range_id = q.cost_range_id
					JOIN target_market m1 ON m1.market_id = q.market_id1
					JOIN target_market m2 ON m2.market_id = q.market_id2					
					WHERE q.query_id = ".$id;			
			$res_head = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
			while($rows1 = mysqli_fetch_row($res_head))
			{
				$dataheader[$x][0] = $rows1[0];
				$dataheader[$x][1] = !empty($rows1[1])?$rows1[1]:'SPC Definition not available';
				$dataheader[$x][2] = !empty($rows1[2])?$rows1[2]:'SPC Instructions not available';
				$dataheader[$x][3] = $rows1[3];
				$dataheader[$x][4] = $rows1[4];
				$dataheader[$x][5] = $rows1[5];
				$dataheader[$x][6] = $rows1[6];
				$dataheader[$x][7] = $rows1[7];
				$dataheader[$x][8] = $rows1[8];
				$dataheader[$x][9] = $rows1[9];
				$dataheader[$x][10] = $rows1[10];
				$dataheader[$x][11] = $rows1[11];
				$dataheader[$x][12] = $rows1[12];
				$dataheader[$x][13] = isset($rows1[13])?$rows1[13]:0;				
				$x++;
			}
			mysqli_free_result($res_head);
			
			// get the body
//                                       CASE WHEN INSTR(v.www, '//') IN (6,7) THEN '' ELSE 'http://' END || v.www AS website,
            $sqlresult = "SELECT  v.vendor_name AS Vendor, 
                                      p.product_name AS Product, 
                                      CONCAT(CASE WHEN INSTR(COALESCE(NULLIF(p.www, ''), v.www), '://') = 0 THEN 'http://' ELSE '' END, COALESCE(NULLIF(p.www, ''), v.www)) AS website, 
                                      s.focus_level AS company_size,
                                      i1.focus_level AS primary_industry,
                                      i2.focus_level AS secondary_industry						 
                              FROM  product p 
                              JOIN  query q ON q.query_id = '$id'
                              JOIN  vendor v ON p.vendor_id = v.vendor_id
                              LEFT JOIN  product_cost_range s ON s.product_id = p.product_id AND s.cost_range_id = q.cost_range_id
                              LEFT JOIN  product_market i1 ON i1.product_id = p.product_id AND i1.market_id = q.market_id1
                              LEFT JOIN  product_market i2 ON i2.product_id = p.product_id AND i2.market_id = q.market_id2 
                              WHERE p.product_id IN ( SELECT pq.product_id
                                                      FROM  product pq
                                                      JOIN query q ON q.query_id = '$id'
                                                      JOIN product_cost_range qs ON qs.cost_range_id = q.cost_range_id AND pq.product_id = qs.product_id AND qs.focus_level BETWEEN 1 AND 3
                                                      JOIN  product_market qi1 ON ((qi1.market_id = q.market_id1 AND qi1.focus_level BETWEEN 1 AND 3) OR q.market_id1 = -1) AND pq.product_id = qi1.product_id 
                                                      JOIN  product_market qi2 ON ((qi2.market_id = q.market_id2 AND qi2.focus_level BETWEEN 1 AND 3) OR q.market_id2 = -1) AND pq.product_id = qi2.product_id
                                                      WHERE pq.status = 1)
                              OR FIND_IN_SET(p.product_id, q.package_id_string) > 0
                              ORDER BY Vendor, Product";

				//echo $sqlresult;
			//echo $sqlresult;exit();
				$result = mysqli_query($this->link, $sqlresult) or die(mysqli_error($this->link));
				while($rows = mysqli_fetch_array($result))
				{
					$databody[$i][0] = $rows[0];
					$databody[$i][1] = $rows[1];
					$databody[$i][2] = $rows[2];
					$databody[$i][3] = $rows[2];
					$databody[$i][4] = ''; // was category
					$databody[$i][5] = self::getImage(1,$rows[3]);
					$databody[$i][6] = self::getImage(1,$rows[4]);
					$databody[$i][7] = self::getImage(1,$rows[5]);					
					$i++;
				}
				mysqli_free_result($result);
		}
		//self::truncateProducts();
		//self::truncateFocus();
		$data['header'] = $dataheader;
		$data['body'] = $databody;
		$data['id'] = $id;
		return $data;
	}
	
	public function getReportForPrint($id)
	{
		//self::truncateProducts();
		//self::truncateFocus();
		$data = array();
		$dataheader = array();
		$databody = array();	
		$i = 0;
		$x = 0;

		if(!empty($this->link))
		{
			
			// update query,set printed column to true
			 
			$sqlupdate = "update query set printed = 1 where query_id=".$id;
			mysqli_query($this->link, $sqlupdate) or die(mysqli_error($this->link));

			// get the header

			$sql = "SELECT	'c.description' AS CategoryDsc, 
					'c.spc_definition' AS SPCDefinition, 
					'c.spc_instructions' AS SPCInstructions,
					r.descript AS CostRangeDsc,
					q.market_id1,
					m1.market_description AS MarketDsc1,
					q.market_id2,
					m2.market_description AS MarketDsc2,
					q.mfg_type_id1,					
					q.focus_level1,
					q.focus_level2,
					q.focus_level3,
					0 as catCount
					FROM query q
					JOIN cost_range r ON r.cost_range_id = q.cost_range_id
					JOIN target_market m1 ON m1.market_id = q.market_id1
					JOIN target_market m2 ON m2.market_id = q.market_id2					
					WHERE q.query_id = ".$id;			
			$res_head = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
			while($rows1 = mysqli_fetch_row($res_head))
			{
				$dataheader[$x][0] = $rows1[0];
				$dataheader[$x][1] = !empty($rows1[1])?$rows1[1]:'SPC Definition not available';
				$dataheader[$x][2] = !empty($rows1[2])?$rows1[2]:'SPC Instructions not available';
				$dataheader[$x][3] = $rows1[3];
				$dataheader[$x][4] = $rows1[4];
				$dataheader[$x][5] = $rows1[5];
				$dataheader[$x][6] = $rows1[6];
				$dataheader[$x][7] = $rows1[7];
				$dataheader[$x][8] = $rows1[8];
				$dataheader[$x][9] = $rows1[9];
				$dataheader[$x][10] = $rows1[10];
				$dataheader[$x][11] = $rows1[11];
				$dataheader[$x][12] = $rows1[12];
				$dataheader[$x][13] = isset($rows1[13])?$rows1[13]:0;				
				$x++;
			}
			mysqli_free_result($res_head);
			
			// get the body
			 
				$sqlresult = 'SELECT  v.vendor_name AS Vendor, 
                                      p.product_name AS Product, 
                                      CONCAT(CASE WHEN INSTR(COALESCE(NULLIF(p.www, \'\'), v.www), \'://\') = 0 THEN \'http://\' ELSE \'\' END, COALESCE(NULLIF(p.www, \'\'), v.www)) AS weburl, 
                                      s.focus_level AS company_size,
                                      i1.focus_level AS primary_industry,
                                      i2.focus_level AS secondary_industry						 
                              FROM  product p 
                              JOIN  query q ON q.query_id = '.$id.'
                              JOIN  vendor v ON p.vendor_id = v.vendor_id
                              LEFT JOIN  product_cost_range s ON s.product_id = p.product_id AND s.cost_range_id = q.cost_range_id
                              LEFT JOIN  product_market i1 ON i1.product_id = p.product_id AND i1.market_id = q.market_id1
                              LEFT JOIN  product_market i2 ON i2.product_id = p.product_id AND i2.market_id = q.market_id2 
                              WHERE p.product_id IN ( SELECT pq.product_id
                                                      FROM  product pq
                                                      JOIN query q ON q.query_id = '.$id.'
                                                      JOIN product_cost_range qs ON qs.cost_range_id = q.cost_range_id AND pq.product_id = qs.product_id AND qs.focus_level BETWEEN 1 AND 3
                                                      JOIN  product_market qi1 ON ((qi1.market_id = q.market_id1 AND qi1.focus_level BETWEEN 1 AND 3) OR q.market_id1 = -1) AND pq.product_id = qi1.product_id 
                                                      JOIN  product_market qi2 ON ((qi2.market_id = q.market_id2 AND qi2.focus_level BETWEEN 1 AND 3) OR q.market_id2 = -1) AND pq.product_id = qi2.product_id
                                                      WHERE pq.status = 1)
                              OR FIND_IN_SET(p.product_id, q.package_id_string) > 0
                              ORDER BY Vendor, Product';

			//echo $sqlresult;exit();
				$result = mysqli_query($this->link, $sqlresult) or die(mysqli_error($this->link));
				while($rows = mysqli_fetch_array($result))
				{
					$databody[$i][0] = $rows[0];
					$databody[$i][1] = $rows[1];
					$databody[$i][2] = $rows[2];
					$databody[$i][3] = $rows[2];
					$databody[$i][4] = ''; // was category
					$databody[$i][5] = self::getImage(1,$rows[3]);
					$databody[$i][6] = self::getImage(1,$rows[4]);
					$databody[$i][7] = self::getImage(1,$rows[5]);					
					// echo $rows[3].','.$rows[4].','.$rows[5].'<br/>';
					$i++;
				}
//				echo $dataheader[$x][4].'<br/>';
//				echo $dataheader[$x][6].'<br/>';
				mysqli_free_result($result);
			//}
			//mysqli_free_result($res);
		}
		//self::truncateProducts();
		//self::truncateFocus();
		
		$data['header'] = $dataheader;
		$data['body'] = $databody;
		$data['id'] = $id;
		return $data;
	}
	
public function getReportData($id) 
{
	$data = array();
	$body = array();
	$row_number = 0;

	if(!empty($this->link))
	{
		// get the header
		$sql = "SELECT
					r.descript,
					m1.market_description AS MarketDsc1,
					m2.market_description AS MarketDsc2,
					q.package_id_string
				FROM query q
				JOIN cost_range r ON r.cost_range_id = q.cost_range_id
				JOIN target_market m1 ON m1.market_id = q.market_id1
				LEFT JOIN target_market m2 ON m2.market_id = q.market_id2					
				WHERE q.query_id = ".$id;			
		$result = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
		if($row = mysqli_fetch_row($result))
		{
			$data['cost_range'] = $row[0];
			$data['primary_industry'] = $row[1];
			$data['secondary_industry'] = $row[2];
			$data['added_product_flag'] = ($row[3] != '' && !empty($row[3]))?1:0;
		}
		mysqli_free_result($result);
						 
		$sql = 'SELECT  v.vendor_name AS Vendor, 
                        p.product_name AS Product, 
                        CONCAT(CASE WHEN INSTR(COALESCE(NULLIF(p.www, \'\'), v.www), \'://\') = 0 THEN \'http://\' ELSE \'\' END, COALESCE(NULLIF(p.www, \'\'), v.www)) AS weburl, 
                        s.focus_level AS company_size,
                        i1.focus_level AS primary_industry,
                        i2.focus_level AS secondary_industry						 
                FROM  product p 
                JOIN  query q ON q.query_id = '.$id.'
                JOIN  vendor v ON p.vendor_id = v.vendor_id
                LEFT JOIN  product_cost_range s ON s.product_id = p.product_id AND s.cost_range_id = q.cost_range_id
                LEFT JOIN  product_market i1 ON i1.product_id = p.product_id AND i1.market_id = q.market_id1
                LEFT JOIN  product_market i2 ON i2.product_id = p.product_id AND i2.market_id = q.market_id2 
                WHERE p.product_id IN ( SELECT pq.product_id
                                         FROM  product pq
                                         JOIN query q ON q.query_id = '.$id.'
                                         JOIN product_cost_range qs ON qs.cost_range_id = q.cost_range_id AND pq.product_id = qs.product_id AND qs.focus_level BETWEEN 1 AND 3
                                         JOIN  product_market qi1 ON ((qi1.market_id = q.market_id1 AND qi1.focus_level BETWEEN 1 AND 3) OR q.market_id1 = -1) AND pq.product_id = qi1.product_id 
                                         JOIN  product_market qi2 ON ((qi2.market_id = q.market_id2 AND qi2.focus_level BETWEEN 1 AND 3) OR q.market_id2 = -1) AND pq.product_id = qi2.product_id
                                         WHERE pq.status = 1)
                OR FIND_IN_SET(p.product_id, q.package_id_string) > 0
                ORDER BY Vendor, Product';

		//echo $sql;exit();
		$result = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
		while($row = mysqli_fetch_array($result))
		{
			$body[$row_number]['vendor'] = $row[0];
			$body[$row_number]['product_name'] = $row[1];
			$body[$row_number]['weburl'] = $row[2];
			$body[$row_number]['company_size'] = $row[3];
			$body[$row_number]['primary_industry'] = $row[4];
			$body[$row_number]['secondary_industry'] = $row[5];
			$row_number++;
		}
		mysqli_free_result($result);
		
		$data['body'] = $body;
	}

	return $data;
}
	
	public function isValid($s,$l,$num)
	{
		$valid = true;
		if($s>$num) $valid = false;
		if($l>$num) $valid = false;
		return $valid;
	}
	
	public function changepass($userid,$newpass)
	{
		$encrypted = sha1($newpass);
		$sql = "update user set password = '$encrypted' where user_id = $userid";
		$res = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
		return $res;
	}
	public function checkpass($userid,$oldpass)
	{
		$encrypted = sha1($oldpass);
		$sql = "select * from user where user_id=$userid and password = '$encrypted'";		
		$res = mysqli_query($this->link, $sql) or die(mysqli_error($this->link));
		return mysqli_num_rows($res);
	}
	
	public function getImage($id,$lvl)
	{
		if($id>0 && ($lvl >= 1 && $lvl <=3))
			return '<IMG src="level'.$lvl.'.gif">';
		else
			return '';
	}
	
	public function truncateProducts()
	{
		if(!empty($this->link))
		{
			$products = mysqli_query($this->link, "truncate products") or die(mysqli_error($this->link));
		}
	}
	
	public function truncateFocus()
	{
		if(!empty($this->link))
		{
			$focus = mysqli_query($this->link, "truncate focus") or die(mysqli_error($this->link));
		}
	}
	
	public function dbclose()
	{
		if(!empty($this->link)) mysqli_close($this->link);
	}
}
?>