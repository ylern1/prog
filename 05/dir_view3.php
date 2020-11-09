<?php

////ディレクトリパス
$img_path = "upload";
// //走査するディレクトリを指定
$directry= new RecursiveDirectoryIterator($img_path);
////指定したディレクトリから反復処理でデータを取得
$files = new RecursiveIteratorIterator($directry);
//var_dump($files);


////画像を繰返し取得表示
foreach ($files as $fileinfo) {
    if ( $fileinfo->isFile() ) {
        $list .= '<img src="'.$fileinfo->getPathname().'"><br>';
    } 
}
echo $list;