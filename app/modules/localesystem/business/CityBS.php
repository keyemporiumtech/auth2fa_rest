<?php
App::uses("AppGenericBS", "modules/cakeutils/classes");
App::uses("City", "Model");

class CityBS extends AppGenericBS {

	function __construct() {
		parent::__construct('City');
	}
	
	function listRegion($id_nation=null){
		$sql= "SELECT DISTINCT regioncode, region FROM cities as City WHERE 1";
		if (! empty($id_nation)) {
			$sql.= " AND City.nation = $id_nation";
		}
		$sql.=" ORDER BY region ASC";
		return $this->query($sql, false);
	}
	
	function listProvince($id_nation=null, $id_region=null){
		$sql= "SELECT DISTINCT provincecode, province FROM cities as City WHERE 1";
		if (! empty($id_nation)) {
			$sql.= " AND City.nation = $id_nation";
		}
		if (! empty($id_region)) {
			$sql.= " AND City.regioncode = '$id_region'";
		}
		$sql.=" ORDER BY province ASC";
		return $this->query($sql, false);
	}
	
	function listCommunity($id_nation=null, $id_region=null, $id_province=null){
		$sql= "SELECT DISTINCT communitycode, community FROM cities as City WHERE City.community <> ''";
		if (! empty($id_nation)) {
			$sql.= " AND City.nation = $id_nation";
		}
		if (! empty($id_region)) {
			$sql.= " AND City.regioncode = '$id_region'";
		}
		if (! empty($id_province)) {
			$sql.= " AND City.provincecode = '$id_province'";
		}
		$sql.=" ORDER BY community ASC";
		return $this->query($sql, false);
	}
}