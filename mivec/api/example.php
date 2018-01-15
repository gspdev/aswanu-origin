<?php
/*
$data_array = array(
    array(
		'title' => 'title1',
		'content' => 'content1',
		'pubdate' => '2009-10-11',
	),
    array(
		'title' => 'title2',
		'content' => 'content2',
		'pubdate' => '2009-11-11',
    )
);
*/

//  属性数组
$attribute_array = array(
    'title' => array(
    'size' => 1
    )
);
$xml = new XMLWriter();
$xml->openUri("php://output");//输出方式，也可以设置为某个xml文件地址，直接输出成文件
$xml->setIndentString('  ');
$xml->setIndent(true);
$xml->startDocument('1.0', 'utf-8');
//  开始创建文件
//  根结点
$xml->startElement('prestashop');
foreach ($data_array as $data) {
	$xml->startElement('order');
	//generation($data);

	if (is_array($data)) {
		foreach ($data as $key => $row) {
			$xml->startElement($key);
			
			if (is_array($row)) {
				foreach ($row as $_ikey=>$_ival) {
					$xml->startElement($_ikey);
					$xml->text($_ival);
					
					foreach ($_ival as $_itemKey=> $_itemVal) {
						$xml->startElement($_itemKey);
						$xml->text($_itemVal);
					}
				}
			}
			
			$xml->text($row);   // 设置内容
			$xml->endElement(); // $key
			
/*			if (isset($attribute_array[$key]) && is_array($attribute_array[$key])) {
				foreach ($attribute_array[$key] as $akey => $aval) {
				//  设置属性值
				//$xml->writeAttribute($akey, $aval);
				}
			}*/
		}
	}
	$xml->endElement(); //items
}
$xml->endElement();
$xml->endDocument();
$xml->flush();

