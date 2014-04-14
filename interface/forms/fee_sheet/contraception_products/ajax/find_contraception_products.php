<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../../../globals.php");
function find_contraceptive_methods($contraceptive_code)
{
    $retval=array();
    $code="IPPFCM:".$contraceptive_code;
    $sqlSearch = "SELECT name,drugs.drug_id,related_code, selector FROM drugs, drug_templates"
              . " WHERE related_code like ? "
              . " AND drug_templates.drug_id=drugs.drug_id AND drugs.active = 1 AND drugs.consumable = 0 "
              . " ORDER BY drugs.name, drug_templates.selector, drug_templates.drug_id";
    $results  =sqlStatement($sqlSearch,array("%".$code."%"));
    while($row=sqlFetchArray($results))
    {
        $rel_codes=explode(";",$row[related_code]);
        $match=false;
        foreach($rel_codes as $cur_code)
        {
            if($cur_code===$code)
            {
                $match=true;
            }
        }
        if($match)
        {
            array_push($retval,array("name"=>$row[name],"drug_id"=>$row[drug_id],"selector"=>$row[selector]));           
        }
    }
    return $retval;
}

function get_method_description($contraceptive_code)
{
    $sqlSearch = " SELECT code_text FROM codes "
               . " WHERE code_type=32 "
               . " AND code=? AND active=1";
    $results = sqlStatement($sqlSearch,array($contraceptive_code));
    if($results)
    {
        return sqlFetchArray($results)['code_text'];
    }
}
if(!acl_check('acct', 'bill'))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "Not authorized for billing";   
    return false;
}

if(isset($_REQUEST['ippfconmeth']))
{
    $ippfconmeth=$_REQUEST['ippfconmeth'];
    $retval['products']=find_contraceptive_methods($ippfconmeth);
    $retval['method']=get_method_description($ippfconmeth);    
}
else
{
    $retval['products']=array();
}


echo json_encode($retval);

?>
