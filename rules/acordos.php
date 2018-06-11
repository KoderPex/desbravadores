<?php
@require_once("../include/functions.php");
responseMethod();

/****************************
 * Methods defined for use. *
 ****************************/
function getQueryByFilter( $parameters ) {
	$where = "";
	$aWhere = array();
	if ( isset($parameters["filters"]) ):
		$keyAnt = "";
		foreach ($parameters["filters"] as $key => $v):
			$not = false;
			if ( isset($parameters["filters"][$key]["fg"]) ):
				$not = strtolower($parameters["filters"][$key]["fg"]) == "true";
			endif;
			$notStr = ( $not ? "NOT " : "" );
			if ( $key == "SA" ):
				$where .= " AND fa.TP ".$notStr."IN";
			else:
				$where .= " AND";
			endif;

			$prim = true;
			$where .= " (";
			if ( is_array( $parameters["filters"][$key]["vl"] ) ):
				foreach ($parameters["filters"][$key]["vl"] as $value):
					if ( empty($value) ):
						$aWhere[] = "NULL";
						$where .= (!$prim ? "," : "" )."?";
					else:
						$aWhere[] = $value;
						$where .= (!$prim ? "," : "" )."?";
					endif;
					$prim = false;
				endforeach;
			else:
				$aWhere[] = "$notStr"."NULL";
				$where .= "?";
			endif;
			$where .= ")";
		endforeach;
	endif;

//echo $where;
//exit;

	$query = "
		SELECT DISTINCT
			fa.ID,
			fa.CD,
			fa.TP,
			cp.NM AS NM_PATR,
			caa.NM AS NM_BENE
		FROM FIN_ACORDO fa
  INNER JOIN CON_PESSOA cp ON (cp.ID_CAD_PESSOA = fa.ID_CAD_PES_PATR)
   LEFT JOIN FIN_ACO_PESSOA fap ON (fap.ID_FIN_ACORDO = fa.ID)
  INNER JOIN CON_PESSOA caa ON (caa.ID_CAD_PESSOA = fap.ID_CAD_PESSOA)
		WHERE fa.NR_ANO = YEAR(NOW()) $where
	 ORDER BY fa.TP
	";
	return CONN::get()->Execute( $query, $aWhere );
}

function getAcordos( $parameters ) {
	$arr = array();

	$result = getQueryByFilter( $parameters );
	foreach ($result as $k => $f):
		$arr[] = array(
			"id" => $f['ID'],
			"cd" => $f['CD'],
			"pt" => $f['NM_PATR'],
			"bn" => $f['NM_BENE'],
			"tp" => $f['TP']
		);
	endforeach;

	return array( "result" => true, "source" => $arr );
}

function getPersonByCPF( $parameters ){
	$arr = array();

	$result = CONN::get()->Execute("
		SELECT cp.ID_CAD_PESSOA, cp.NM, cp.DT_NASC, cp.FONE_CEL, cp.EMAIL 
		  FROM CON_PESSOA cp
		 WHERE cp.IDADE_HOJE > ?
	", array(15) );
	if (!$result->EOF):
		$arr[] = array(
			"id" => $result->fields['ID_CAD_PESSOA'],
			"nm" => $result->fields['NM'],
			"dt" => is_null($result->fields['DT_NASC']) ? "" : date( 'd/m/Y', strtotime($result->fields['DT_NASC']) ),
			"fn" => $result->fields['FONE_CEL'],
			"em" => $result->fields['EMAIL']
		);
	endif;

	return array( "result" => true, "source" => $arr );
}

function getPatrocinadores($parameters){
	$arr = array();

	$where = "(cp.DT_NASC IS NULL OR cp.IDADE_HOJE > ?)";
	$aWhere = array(PATTERNS::getBars()->getClubeID(), 15);

	if ( isset($parameters["query"]) ):
		$nome = mb_strtoupper($parameters["query"]);
		$where .= " AND cp.NM LIKE '%$nome%'";
	endif;

	$result = CONN::get()->Execute("
		SELECT DISTINCT cp.ID_CAD_PESSOA, cp.NM, cp.DT_NASC, cp.NR_CPF, cp.FONE_CEL, cp.EMAIL, cp.TP_SEXO
		  FROM CON_PESSOA cp
	 LEFT JOIN CAD_MEMBRO cm ON (cm.ID_CAD_PESSOA = cp.ID_CAD_PESSOA AND cm.ID_CLUBE = ?)
	 LEFT JOIN CAD_ATIVOS ca ON (ca.ID_CAD_MEMBRO = cm.ID)
		 WHERE $where
	  ORDER BY ca.NR_ANO, cp.NM
	", $aWhere );
	foreach ($result as $k => $f):
		$arr[] = array(
			"id" => $f['ID_CAD_PESSOA'],
			"nm" => $f['NM'],
			"dt" => is_null($f['DT_NASC']) ? "" : date( 'd/m/Y', strtotime($f['DT_NASC']) ),
			"sx" => $f['TP_SEXO'],
			"cp" => $f['NR_CPF'],
			"fn" => $f['FONE_CEL'],
			"em" => $f['EMAIL']
		);
	endforeach;

	return array( "result" => true, "source" => $arr );
}
?>
