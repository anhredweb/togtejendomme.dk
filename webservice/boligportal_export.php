<?php

define( '_JEXEC', 1 );
define( '_VALID_MOS', 1 );
define( 'JPATH_BASE', realpath(dirname(__FILE__) .'/../' ) );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'HTTP_BASE_ADD', 'http://www.togtejendomme.dk');
define( 'GALLERY_PATH', HTTP_BASE_ADD.'/components/com_reditem/assets/images/customfield/');
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );


echo "<h1>This is the XML export file - Welcome</h1>";

$fileName = "boligportal_export.xml";
$fileHandle = fopen($fileName, 'w') or die('No file');

// Write xml version declaration
$stringData = '<?xml version="1.0" encoding="windows-1252"?>'.PHP_EOL;
fwrite($fileHandle, $stringData);

// Write intro 
$stringData = '<annoncer xmlns="http://www.boligportal.dk" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.boligportal.dk/boligportal.xsd">'.PHP_EOL;
	
fwrite($fileHandle, $stringData);
$db = JFactory::getDbo();
$query = $db->getQuery(true);

// Retrieve all published items
 $query = "SELECT `extra`.`itemid`, UNIX_TIMESTAMP( `base`.`modified_time` ) AS `checkouttime`, `base`.`title`, `base`.`fulltext`, `base`.`published`, `extra`.`depositum`, `extra`.`leje_pr_maned_ekskl_forbrug` as `husleje`, `extra`.`forudbetalt_leje`, `extra`.`kvm`, UNIX_TIMESTAMP(`extra`.`indflytningsdato`) AS `indflytningsdato`, `extra`.`husdyr`, `extra`.`dor`, `extra`.`side`, `extra`.`etage`, `extra`.`bogstav`, `extra`.`postnr`, `extra`.`vejnavn`, `extra`.`husnummer`, `extra`.`type`, `extra`.`antal_vaerelser`, `extra`.`lejeperiode`, `extra`.`delevenlig`, `extra`.`gallery`
   			FROM `jos_reditem_items` AS `base`
   			INNER JOIN (
    			SELECT *
    			FROM `jos_reditem_types_reditem`
   			) AS `extra` ON `base`.`id` = `extra`.`id`
   			WHERE `base`.`published` = 1";

// Reset the query using our newly populated query object.
$db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
$row = $db->loadAssocList();
$num_rows = sizeof($row);
echo "Number of records found : ".$num_rows."<br />";

$kontaktoplysninger = array(
		"email" => "keg@togtejendomme.dk",
		"telefon1" => "70701320"
	);
$tmp_id = 0;
for ($i = 0; $i < $num_rows; $i++)
{
	// Begin annonce tag
	if ($row[$i]['itemid'] == '')
	{
		$tmp_id++;
		$row[$i]['itemid'] = $tmp_id;
	}
	$stringData = "\t".'<annonce ver="1.2">'.PHP_EOL;
	fwrite($fileHandle, $stringData);

	// Write storudlejer tag
	$stringData = "\t\t"."<storudlejerid>".$row[$i]['itemid']."</storudlejerid>".PHP_EOL;
	fwrite($fileHandle,$stringData);

	// Write sidstrettetdato tag
	$stringData = "\t\t"."<sidstrettetdato>".$row[$i]['checkouttime']."</sidstrettetdato>".PHP_EOL;
	fwrite($fileHandle, $stringData);

	// Write bolig tag - begin
	$stringData = "\t\t".'<bolig depositum="'. $row[$i]['depositum'] .'" forudbetaltleje="' . $row[$i]['forudbetalt_leje'] . '">'.PHP_EOL;
	fwrite($fileHandle,$stringData);

	// Write type
	$itemTypeDB = utf8_decode($row[$i]['type']);
	$itemType = "Not defined";
	switch ($itemTypeDB) {
	    case "Lejlighed":
	        $itemType = $row[$i]['antal_vaerelser']."-værelses lejlighed";
	        break;
	    case "Erhverv":
	        $itemType = "Erhvervslejemål";
	        break;
	    case "Parkering":
	        $itemType = "Parkeringsplads";
	        break;
	}
	$stringData = "\t\t\t".'<type>'.utf8_decode($itemType).'</type>'.PHP_EOL;
	fwrite($fileHandle, $stringData);

	// Write husleje
	$stringData = "\t\t\t".'<husleje>'.utf8_decode($row[$i]['husleje']).'</husleje>'.PHP_EOL;
	fwrite($fileHandle, $stringData);

	// Write kvm
	$stringData = "\t\t\t".'<kvm>'.utf8_decode($row[$i]['kvm']).'</kvm>'.PHP_EOL;
	fwrite($fileHandle, $stringData);
	
	// Write overtagelsesdato
	$nowIs = time();
	if ($row[$i]['indflytningsdato'] < $nowIs)
	{
		$row[$i]['indflytningsdato'] = 0;
	}
	$stringData = "\t\t\t".'<overtagelsesdato>'.utf8_decode($row[$i]['indflytningsdato']).'</overtagelsesdato>'.PHP_EOL;
	fwrite($fileHandle, $stringData);

	// Write moebleret
	$stringData = "\t\t\t".'<moebleret>2</moebleret>'.PHP_EOL;
	fwrite($fileHandle, $stringData);

	// Write husdyr (always "Nej")
	$husdyr = 2;
	if ($row[$i]['husdyr'] == 'Ja')
	{
		$husdyr = 1;
	}
	$stringData = "\t\t\t".'<husdyr>'.$husdyr.'</husdyr>'.PHP_EOL;
	fwrite($fileHandle, $stringData);
	
	// Write delevenlig
	$delevenlig = 2; // Defaults to "Nej"
	if (utf8_decode($row[$i]['delevenlig']) == 'Ja')
	{
		$delevenlig = 1;
	}
	$stringData = "\t\t\t".'<delevenlig>'.$delevenlig.'</delevenlig>'.PHP_EOL;
	fwrite($fileHandle, $stringData);

	// Write adresse - BEGIN - TODO : add attributes
	if(strlen(utf8_decode($row[$i]['side'])) < 2)
	{
		$row[$i]['side'] = "";
	}
	if(strlen(utf8_decode($row[$i]['etage'])) < 2)
	{
		$row[$i]['etage'] = "";
	}
	 
	$stringData = "\t\t\t".'<adresse doer="'.utf8_decode($row[$i]['dor']).'" side="'.utf8_decode($row[$i]['side']).'" etage="'.utf8_decode($row[$i]['etage']).'" bogstav="'.utf8_decode($row[$i]['bogstav']).'" >'.PHP_EOL;
	fwrite($fileHandle, $stringData);

	// Write postnummer
	$stringData = "\t\t\t\t".'<postnummer>'.utf8_decode($row[$i]['postnr']).'</postnummer>'.PHP_EOL;
	fwrite($fileHandle, $stringData);

	// Write vejnavn
	$stringData = "\t\t\t\t".'<vejnavn>'.utf8_decode($row[$i]['vejnavn']).'</vejnavn>'.PHP_EOL;
	fwrite($fileHandle, $stringData);	

	// Write husnummer
	$stringData = "\t\t\t\t".'<husnummer>'.utf8_decode($row[$i]['husnummer']).'</husnummer>'.PHP_EOL;
	fwrite($fileHandle, $stringData);

	// Write adresse - END	
	$stringData = "\t\t\t".'</adresse>'.PHP_EOL;
	fwrite($fileHandle, $stringData);

	// Write bolig tag - end
	$stringData = "\t\t".'</bolig>'.PHP_EOL;
	fwrite($fileHandle,$stringData);

	// Write lejeperiode
	$lejeperiode = utf8_decode($row[$i]['lejeperiode']);
	switch($lejeperiode)
	{
		case "2-12 måneder":
			$lejeperiode = 1;
			break;
		case "1-2 år":
			$lejeperiode = 2;
			break;
		case "Ubegrænset":
			$lejeperiode = 3;
			break;
		case "2 år+":
			$lejeperiode = 4;
			break;
		default:
			$lejeperiode = 3;
			break;
	}

	$stringData = "\t\t".'<lejeperiode>'. $lejeperiode .'</lejeperiode>'.PHP_EOL;
	fwrite($fileHandle,$stringData);

	// Write overskrift
	$stringData = "\t\t".'<overskrift>'. strip_tags(utf8_decode($row[$i]['title'])) .'</overskrift>'.PHP_EOL;
	fwrite($fileHandle,$stringData);

	// Write tekst
	$stringData = "\t\t".'<tekst>'. strip_tags(utf8_decode($row[$i]['fulltext'])) .'</tekst>'.PHP_EOL;
	fwrite($fileHandle,$stringData);

	// Write kontakt - BEGIN
	$stringData = "\t\t".'<kontakt>'.PHP_EOL;
	fwrite($fileHandle,$stringData);

	// Write email
	$stringData = "\t\t\t".'<email>'. $kontaktoplysninger['email'] . '</email>'.PHP_EOL;
	fwrite($fileHandle,$stringData);

	// Write telefon1
	$stringData = "\t\t\t".'<telefon1>'. $kontaktoplysninger['telefon1'] . '</telefon1>'.PHP_EOL;
	fwrite($fileHandle,$stringData);

	// Write kontakt - END
	$stringData = "\t\t".'</kontakt>'.PHP_EOL;
	fwrite($fileHandle,$stringData);

	// Write billeder - BEGIN
	$images = $row[$i]['gallery'];
	
	if (strpos($images,".jpg") !== false)
	{
		$stringData = "\t\t".'<billeder>'.PHP_EOL;
		fwrite($fileHandle,$stringData);
		$imageArr = explode(",", $images);
		//echo $images."<br />";
		foreach ($imageArr as $key => $value)
		{
			//echo "[".$key."]"."[".$value."]<br />";
			//echo $value."<br />";
			//$imagePathArr = explode(":", $value);
			$replaceArray = array('[',']','"','{','}','\\');
			//$imagePath = GALLERY_PATH.str_replace($replaceArray, "", stripslashes($imagePathArr[1]));
			$imagePath = GALLERY_PATH.str_replace($replaceArray, "", stripslashes($value));
			//echo $imagePath."<br />";
			$stringData = "\t\t\t".'<billede>'.PHP_EOL;
			fwrite($fileHandle,$stringData);
			$stringData = "\t\t\t\t".'<url>'.$imagePath.'</url>'.PHP_EOL;
			fwrite($fileHandle,$stringData);
			$stringData = "\t\t\t\t".'<timestamp>'.$row[$i]['checkouttime'].'</timestamp>'.PHP_EOL;
			fwrite($fileHandle,$stringData);
			$stringData = "\t\t\t".'</billede>'.PHP_EOL;
			fwrite($fileHandle,$stringData);
		}
		
		// Write billeder - END
		$stringData = "\t\t".'</billeder>'.PHP_EOL;
		fwrite($fileHandle,$stringData);
	}
	

	// End annonce tag
	$stringData = "\t".'</annonce>'.PHP_EOL;
	fwrite($fileHandle,$stringData);
}
$stringData = "</annoncer>";
fwrite($fileHandle, $stringData);
fclose($fileHandle);
?>