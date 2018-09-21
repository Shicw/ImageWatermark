<?php
/**
 * PHP为图片添加水印
 * 并转为Base64,返回给ajax
 * By Shicw
 */
$img= $_FILES['image']['tmp_name'];//接收图片信息
$content = $_POST['content'];//接收水印内容
//获取图片信息,其中索引为2的数组值为照片的类型号
$info = getimagesize($img);
//通过类型号获取图片的类型(jpg/png)
$type = image_type_to_extension($info[2],false);
//创建一块画布,并将获取到的图片导入到画布中
//由于imagecreatefrom系列函数包含jpg,png等格式
//所以需要根据上面获取到的图片类型来选择不同的函数处理
$ext = "imagecreatefrom{$type}";
$newImage = $ext($img);
//设置颜色和透明度
$color = imagecolorallocatealpha($newImage, 0, 0, 0, 90);
//设置图片文字位置,大小,字体,透明度等信息
imagettftext($newImage, 30, 0, 20, 80, $color, 'ttf/consolas.ttf', $content);
header("content-type:" . $info['mime']);
$func = "image{$type}";
ob_start();
$func($newImage);//imagepng或imagejpeg处理后的图片
$imageData = ob_get_contents ();
ob_end_clean ();
$base64 = "data:image/".$type.";base64,". base64_encode ($imageData);//将图片转为base64
echo json_encode($base64);