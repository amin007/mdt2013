<?php

class Kawalan_Tanya extends Tanya 
{

	public function __construct() 
	{
		parent::__construct();
		$this->_susun = ' ORDER BY nama';
	}

	public function paparMedan($myTable, $papar = null)
	{
		$cari = ( !isset($papar) ) ? '' : ' WHERE  ' . $papar . ' ';
		//return $this->db->select('SHOW COLUMNS FROM ' . $myTable);
		$sql = 'SHOW COLUMNS FROM ' . $myTable . $cari;
		
		//echo $sql . '<br>';
		return $this->db->selectAll($sql);
	}

	public function kiraKes($sv, $myTable, $medan, $fe)
	{
		$carife = ( !isset($fe) ) ? '' : ' WHERE fe = "' . $fe . '"';

		$sql = 'SELECT ' . $medan . ' FROM ' 
			 . $sv . $myTable . $carife . $this->_susun;
		
		//echo $sql . '<br>';
		$result = $this->db->rowcount($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function paparSemua($sv, $myTable, $medan, $fe, $jum)
	{
		//$jum['dari'] . ', ' . $jum['max']
		$carife = ( !isset($fe) ) ? '' : ' WHERE fe = "' . $fe . '"';
		$sql = 'SELECT ' . $medan . ' FROM ' 
			 . $sv . $myTable . ' b ' . $carife . $this->_susun;
		
		//echo $sql . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function kesSemua($sv, $myTable, $medan, $fe, $jum)
	{
		//$jum['dari'] . ', ' . $jum['max']
		$carife = ( !isset($fe) ) ? '' : ' WHERE fe = "' . $fe . '"';

		$sql = 'SELECT ' . $medan . ' FROM ' . 
			$sv . $myTable . ' as b ' . $carife . $this->_susun .
			' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];

		//echo $sql . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function kesSelesai($sv, $myTable, $medan, $fe, $jum)
	{
		//$jum['dari'] . ', ' . $jum['max']
		$carife = ( !isset($fe) ) ? '' : ' and fe = "' . $fe . '"';
		$a1 = ($myTable == 'rangka13') ?
			' respon = "A1"' : ' terima is not null';

		$sql = 'SELECT ' . $medan . ' FROM ' . $sv . $myTable 
			 . ' b WHERE' . $a1 . $carife . $this->_susun 
			 . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];

		//echo $sql . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}
	
	public function kesJanji($sv, $myTable, $medan, $fe, $jum)
	{
		$carife = ( !isset($fe) ) ? '' : ' and b.fe = "' . $fe . '"';

		$sql = 'SELECT ' . $medan . ' FROM ' . $sv . $myTable 
		     . ' b, `' . $sv .'rangka13` as c WHERE b.newss = c.newss '
			 . ' and (b.terima is null and c.respon != "A1") ' 
			 . $carife . $this->_susun 
			 . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];

		//echo $sql . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function kesBelum($sv, $myTable, $medan, $fe, $jum)
	{
		//$jum['dari'] . ', ' . $jum['max']
		$carife = ( !isset($fe) ) ? '' : ' and fe = "' . $fe . '"';

		$sql = 'SELECT ' . $medan . ' FROM ' . $sv . $myTable 
			 . ' as b WHERE (terima is null' 
			 . ' or terima like "0000%") ' . $carife 
			 . $this->_susun 
			 . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];

		//echo $sql . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function kesTegar($sv, $myTable, $medan, $fe, $jum)
	{
		//$jum['dari'] . ', ' . $jum['max']
		$carife = ( !isset($fe) ) ? '' : ' and fe = "' . $fe . '"';

		$sql = 'SELECT ' . $medan . ' FROM ' 
			 . $sv . $myTable . ' WHERE (`respon` not like "A1"' 
			 . ' and `respon` not like "B%") ' . $carife 
			 . $this->_susun 
			 . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];

		//echo $sql . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function kiraKesUtama($myTable, $medan, $cari)
	{
		$cariUtama = ( !isset($cari['utama']) ) ? 
		'' : ' WHERE b.newss=c.newss and b.utama = "' . $cari['utama'] . '"';
		$cariFe = ( !isset($fe) ) ? '' : ' and b.fe = "' . $fe . '"';
		$respon = ( !isset($cari['respon']) ) ? null : $cari['respon'] ;
		$AN=array('A2','A3','A4','A5','A6','A7','A8','A9','A10','A11','A12','A13');
		
		if  ($respon=='a1')
			$cariRespon = " AND c.respon='A1' and b.terima like '20%' \r";
		elseif ($respon=='xa1')
			$cariRespon = " AND b.terima is null \r";
		elseif ($respon=='tegar')
			$cariRespon = " AND(`respon` IN ('" . implode("','",$AN) . "')) \r";
		else $cariRespon = '';

		$sql = 'SELECT ' . $medan . ' FROM ' . 	$myTable 
			 . ' b, `mdt_rangka13` as c '
			 . $cariUtama . $cariRespon . $cariFe;

		//echo $sql . '<br>';
		$result = $this->db->rowcount($sql);
		//echo json_encode($result);
		
		return $result;
	}
	
	public function kesUtama($myTable, $medan, $cari, $jum)
	{
		//$jum['dari'] . ', ' . $jum['max']
		$cariUtama = ( !isset($cari['utama']) ) ? 
		'' : ' WHERE b.newss=c.newss and b.utama = "' . $cari['utama'] . '"';
		$respon = ( !isset($cari['respon']) ) ? null : $cari['respon'] ;
		$cariFe = ( !isset($fe) ) ? '' : ' and b.fe = "' . $fe . '"';
		$AN=array('A2','A3','A4','A5','A6','A7','A8','A9','A10','A11','A12','A13');
		
		if  ($respon=='a1')
			$cariRespon = " AND c.respon='A1' and b.terima like '20%' \r";
		elseif ($respon=='xa1')
			$cariRespon = " AND b.terima is null \r";
		elseif ($respon=='tegar')
			$cariRespon = " AND(`c.respon` IN ('" . implode("','",$AN) . "')) \r";
		else $cariRespon = '';

		$sql = 'SELECT ' . $medan . ' FROM ' . 	$myTable 
			 . ' b, `mdt_rangka13` as c '
			 . $cariUtama . $cariRespon . $cariFe
			 . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];

		//echo $sql . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function kesSemak($myTable, $myJoin, $medan, $jum)
	{
		//$jum['dari'] . ', ' . $jum['max']
		$sql = 'SELECT ' . $medan . ' FROM ' 
			 . $myTable . ' a, '.$myJoin.' b ' 
			 . ' WHERE a.newss=b.newss ' 
			 . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];
			
		$result = $this->db->selectAll($sql);
		//echo '<pre>' . $sql . '</pre><br>';
		//echo json_encode($result);
		
		return $result;
	}
	
	public function cariMedan($myTable, $medan, $cari)
	{
		$cariMedan = ( !isset($cari['medan']) ) ? '' : $cari['medan'];
		$cariID = ( !isset($cari['id']) ) ? '' : $cari['id'];
		
		$sql = 'SELECT ' . $medan . ' FROM ' . $myTable 
			 . ' WHERE ' . $cariMedan . ' like "%' . $cariID . '%" ';
		//' WHERE ' . $medan . ' like %:cariID% ', array(':cariID' => $cariID));

		//echo $sql . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}
	
	public function cariSemuaMedan($myTable, $medan, $cari)
	{
		$cariMedan = ( !isset($cari['medan']) ) ? '' : $cari['medan'];
		$cariID = ( !isset($cari['id']) ) ? '' : $cari['id'];
		
		$sql = 'SELECT ' . $medan . ' FROM ' . 	$myTable 
			 . ' WHERE ' . $cariMedan . ' = "' . $cariID . '" ';
		
		//echo $sql . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function cariSatuSahaja($myTable, $medan, $cari)
	{
		$cariMedan = ( !isset($cari['medan']) ) ? '' : $cari['medan'];
		$cariID = ( !isset($cari['id']) ) ? '' : $cari['id'];
		
		$sql = 'SELECT ' . $medan . ' FROM ' . 	$myTable 
			 . ' WHERE ' . $cariMedan . ' = "' . $cariID . '" ';
		
		//echo $sql . '<br>';
		$result = $this->db->select($sql);
		//echo json_encode($result);
		
		return $result;
	}
	
	public function cariIndustri($myTable, $medan, $cari)
	{
		$cariMedan = ( !isset($cari['medan']) ) ? '' : $cari['medan'];
		$cariID = ( !isset($cari['id']) ) ? '' : $cari['id'];
		
		$sql = 'SELECT ' . $medan . ' FROM ' . $myTable 
			 . ' WHERE ' . $cariMedan . ' = "' . $cariID . '" ';
		
		//echo $sql . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}
	
	public function kiraProsesan($myTable)
	{
		$sql = 'SELECT * FROM ' . $myTable 
			 . ' WHERE data12 <> "Batch 1" ';
		
		//echo $sql . '<br>';
		$result = $this->db->rowcount($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function semakProsesan($myTable)
	{
		$sql = 'SELECT * FROM ' . 	$myTable 
			 . ' WHERE data12 <> "Batch 1" ';
		
		//echo $sql . '<br>';
		$result = $this->db->select($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function semakRangkaProsesan($myTable, $medan, $cari, $jum)
	{
		$cariMedan = ( !isset($cari['medan']) ) ? '' : $cari['medan'];
		$cariID = ( !isset($cari['id']) ) ? '' : $cari['id'];
		$cari = ( !isset($cari['medan']) ) ? '' 
			: ' and ' . $cariMedan . ' = "' . $cariID . '" ';
		
		$sql = 'SELECT ' . $medan . ' FROM ' . 	$myTable 
			 . ' WHERE data12 <> "Batch 1" ' 
			 . $cari . ' LIMIT ' . $jum['dari'] . ', ' . $jum['max'];
		
		//echo $sql . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function ubahSimpan($data, $myTable)
	{
		//echo '<pre>$sql->', print_r($data, 1) . '</pre>';
		$senarai = null;
		$medanID = 'newss';
		
		foreach ($data as $medan => $nilai)
		{
			//$postData[$medan] = $nilai;
			if ($medan == $medanID)
				$cariID = $medan;
			elseif ($medan != $medanID)
				$senarai[] = ($nilai==null) ? " `$medan`=null" : " `$medan`='$nilai'"; 
			if(($medan == 'fe'))
				$fe = ($nilai==null) ? " `$medan`=null" : " `$medan`='$nilai'"; 
		}
		
		$senaraiData = implode(",\r",$senarai);
		$where = "`$cariID` = '{$data[$cariID]}' ";
		
		// set sql
		$sql = " UPDATE `$myTable` SET \r$senaraiData\r WHERE $where";
		//echo '<pre>$sql->', print_r($sql, 1) . '</pre>';
		$this->db->update($sql);
	}

	public function tambahSimpan($data, $myTable)
	{
		//echo '<pre>$sql->', print_r($data, 1) . '</pre>';
		//$fieldNames = implode('`, `', array_keys($data));
		//$fieldValues = ':' . implode(', :', array_keys($data));

		$senarai = null;
		
		foreach ($data as $medan => $nilai)
		{
			$senarai[] = ($nilai==null) ? " `$medan`=null" : " `$medan`='$nilai'"; 
		}
		
		$senaraiData = implode(",\r",$senarai);
		
		// set sql
		$sql = " INSERT `$myTable` SET $senaraiData";
		//echo '<pre>$sql->', print_r($sql, 1) . '</pre>';
		$this->db->insert($sql);
	}
	
	public function cantumsql($sql) 
	{
		//echo $sql . '<br>';
		$result = $this->db->selectAll($sql);
		//echo json_encode($result);
		
		return $result;
	}

	public function cariKawalan($bulan, $cari, $apa)
	{
		foreach ($bulan as $key => $myTable)
		{// mula ulang table
			# untuk sql biasa				
			if (!in_array($key,array(0,1)))
			{
				////////////////////////////////////////////////////////
				//if (isset($myTable)){$sebelum = (array_search($myTable,$bulan))-1;}
				$sebelum = ($key - 1);
				$msic='if(semasa.msic is null,semasa.msic,semasa.msic)';
				$k1 = '<p align="right">';
				$k2 = '</p>';
				//echo '<hr>'.$key.')Bandingan Antara Bulan ' . $myTable . ' Dan ' . $bulan[$sebelum];
				// hasil+lain
				$hasil="concat( '$k1', format(lepas.hasil,0),'|',format(semasa.hasil,0),'$k2' ) as `hasil`";
				$dptLain="concat( format(lepas.dptLain,0),'|',format(semasa.dptLain,0) ) as `dptLain`";
				$peratus="format((((semasa.hasil-lepas.hasil)/lepas.hasil)*100),2)";
				$jumSemasa = 'format(semasa.hasil+semasa.dptLain, 0)';
				$jumLepas = 'format(lepas.hasil+lepas.dptLain, 0)';
				$jumlah="format((( ($jumSemasa - $jumLepas) / $jumLepas)*100),2)";
				// gaji
				$gajilepas="format(lepas.gaji,0)";
				$gajisemasa="format(semasa.gaji,0)";
				$gajiperatus="format((((semasa.gaji-lepas.gaji)/lepas.gaji)*100),2)";
				// staf
				$staflepas="format(lepas.staf,0)";
				$stafsemasa="format(semasa.staf,0)";
				$stafperatus="format((((semasa.staf-lepas.staf)/lepas.staf)*100),2)";
				//sql
				$sql = "SELECT semasa.newss,semasa.nama,$msic msic,semasa.utama,semasa.fe,\r"
					 . "$hasil,\r$dptLain,\r$peratus as `peratus`,\r"
					 . "concat($jumLepas,'|',$jumSemasa) as `Hasil Semua`,\r$jumlah as peratus2,"
					 . "concat($gajilepas,'|',$gajisemasa) as gaji,\r$gajiperatus as `gaji%`,\r"
					 . "concat($staflepas,'|',$stafsemasa) as staf,\r$stafperatus as `staf%`,\r"
					 . "semasa.sebab, substring('$myTable', 5, 5) as bulan\r"
					 . "FROM " . $bulan[$sebelum] . " lepas, $myTable semasa\r"
					 . "WHERE lepas.newss=semasa.newss "
					 . "AND semasa.$cari='$apa'\r";
				////////////////////////////////////////////////////////	
					//echo '<pre>$sql:'; print_r($sql) . '</pre><hr>';
					$data['Kawal'][] = $this->db->select($sql);
			}
			elseif (in_array($key,array(0)))
			{
				$sql = "\rSELECT * FROM `$myTable` WHERE $cari='$apa'\r";
								
				//echo '<pre>$sql:'; print_r($sql) . '</pre><hr>';
				$data['Rangka'] = $this->db->selectAll($sql);
			}// tamat if ($key != 0 || $key != 1)
		/////////////////////////////////////////////////////////////////////
			if ($key!='0')
			{
				$cantum[$key] = "SELECT newss,substring('$myTable', 5, 5) as bulan,"
					 . ' nama,msic,terima,format(hasil,0) hasil,format(dptLain,0) dptLain,'
					 . 'web,format(stok,0) stok,format(staf,0) staf,format(gaji,0) gaji,sebab,outlet'
					 . "\r FROM $myTable WHERE $cari='$apa'";
				$cantum2[$key] = "SELECT newss,substring('$myTable', 5, 5) as bulan,"
					 . ' nama,msic,terima,hasil,dptLain,'
					 . 'web,stok,staf,gaji,sebab,outlet'
					 . "\r FROM $myTable WHERE $cari='$apa'";
			}
			
		}// tamat ulang table
		$cantumSql = implode("\rUNION\r", $cantum);
		$cantumSql2 = implode("\rUNION\r", $cantum2);
		$sql3 = $cantumSql . "\rUNION\r\r"
			  . 'SELECT newss,\'JUM\' as bulan,nama,msic,terima,format(sum(hasil),0) hasil,format(sum(dptLain),0),web,'
			  . 'format(sum(stok),0) stok,format(sum(staf)/12,0) staf,format(sum(gaji),0) gaji, \'JUM\' AS sebab, outlet'
			  . "\r FROM ($cantumSql2) as JUMLAH \rUNION\r\r"
			  . 'SELECT newss,\'PURATA\' as bulan,nama,msic,terima,format(sum(hasil)/12,0) hasil,format(sum(dptLain)/12,0),web,'
			  . 'format(sum(stok)/12,0) stok,format(sum(staf)/12,0) staf,format(sum(gaji)/12,0) gaji, sebab, outlet'
			  . "\r FROM ($cantumSql2) as PURATA";
		//echo '<pre>$sql3:'; print_r($sql3) . '</pre><hr>';
		
		$data['Papar'] = $this->db->selectAll($sql3);
		//echo '<pre>$data:'; print_r($data) . '</pre>';
		//return $this->db->selectAll($sql);
		return $data;
	}
	
	public function xhrInsert() 
	{
		$text = $_POST['text'];
		$this->db->insert('data', array('text' => $text));
		$data = array('text' => $text, 'id' => $this->db->lastInsertId());
		echo json_encode($data);
	}
	
	public function xhrGetListings()
	{
		$result = $this->db->select("SELECT * FROM data");
		//echo $result;
		echo json_encode($result);
	}
	
	public function xhrDeleteListing()
	{
		$id = (int) $_POST['id'];
		$this->db->delete('data', "id = '$id'");
	}

}