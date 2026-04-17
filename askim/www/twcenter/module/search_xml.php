<? include $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php"; ?>
<?
$sql = "select * from wiz_product where prdname like '%".$prdname."%' or info_name6 like '%".$prdname."%' group by prdname";
$result = query($sql) or error("sql_error");
$out = "";
$out  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$out .= "<nodeRoot>\n";
while ($row = sql_fetch_arr($result)){
$out .= "  <nodeFirstChild>\n";
$out .= "    <idx>$row['prdcode']</idx>\n";
$out .= "    <pro_name><![CDATA[ ".eregi_replace($prdname,"<font color=red>".strtoupper($prdname)."</font>",cut_str($row['prdname'],36))."]]></pro_name>\n";
$out .= "    <prd_name>".strip_tags($row['prdname'])."</prd_name>\n";
$out .= "    <sql>$sql</sql>\n";
$out .= "  </nodeFirstChild>\n";
}
$out .= "</nodeRoot>\n";
header( "Content-type: application/xml; charset=utf-8" );
echo $out;

?>