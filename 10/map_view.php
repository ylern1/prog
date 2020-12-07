<?php
 //**********************************************************
 // *  fileupload2.php
 // *  FileList（画像ファイル一覧表示）
 //**********************************************************
//１．DB接続
try {
    //dbname=gs_db
    //host=localhost
    //Password:MAMP='root', XAMPP=''
    $pdo = new PDO('mysql:dbname=map_db;charset=utf8;host=localhost','root','root');
} catch (PDOException $e) {
    exit('DB_Connect Error:'.$e->getMessage()); //DB接続：Error表示
}

//SELECT文を作る
$sort  = "input_date"; //SQL:SORT用
$sql = "SELECT * FROM map_tables ORDER BY :sort DESC"; //SQL文
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":sort",  $sort);//SQL:SORT用：サニタイジング(無効化)
$status = $stmt->execute(); //上はセット、ここでSQL実行

//JS用！ 配列文字列を作成
$img=""; //画像名の配列文字列
$lat=""; //lat:緯度の配列数値
$lon=""; //lon:経度の配列数値
//let a = []

$i=0;


if($status==false){
    //SQLエラーの文字列を作成   
    $view = "SQLError"; //SQLエラーの文字列を作成
}else{    
    //配列のインデックスで使用する変数iを作成

    //取得したレコード数ループでデータ取得
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    
        if($i==0){
            //1回目のみ実行
            $img .= '"'.$row["img"].'"';
            $lat .= $row["lat"];
            $lon .= $row["lon"];
        }else{
            //2回目以降はこちら（2回目から先頭にカンマを付与）
            $img .= ',"'.$row["img"].'"';
            $lat .= ",".$row["lat"];
            $lon .= ",".$row["lon"];
        }
        //$iをインクリメント
        $i++;
    }
    // echo $img
    // exit;
}



?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>写真アップロード</title>
<style>
html,body{width:100%;height:100%;margin:0;padding:0;}
h1{padding:0;margin:0;font-size:50%;}
#photarea{padding:5%;width:100%;background:black;}
img{height:100px;}
</style>
<link rel="stylesheet" href="css/range.css">
<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body id="main">


<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <div class="navbar-header"><a class="navbar-brand" href="#">写真アップロード</a></div>
      <ul class="pager">
      <li class="previous"><a href="file_chek.html">← カメラ／写真選択</a></li>
      <li class="next disabled"><a href="file_view.php">画像一覧</a></li>
      </ul>
     </div>
  </nav>
</header>
<!-- Head[End] -->


<!-- IMG_LIST[Start] -->
<div id="myMap" style="width:700px;height:600px;"></div>
<!-- IMG_LIST[END] -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=AiHP5qUs_qc_hf4eoXeByC4gx8wW170E53R3eWNFdvVZw7oJK8UIi5njE_DXwi3I' async defer></script>
<script src="js/BmapQuery.js"></script>
<script>
//1.配列
let img = [<?=$img?>];
let lat = [<?=$lat?>];
let lon = [<?=$lon?>];
let name = [<?=$name?>];

//2.BingMapライブラリを読み込んだらGetmap関数を実行！
let map;
//* MapObjectをグローバルで保持
//* MapZoom値
let zoom = 11;

function GetMap(){
    //BingMapスタート
    map = new Bmap("#myMap");
    map.startMap(47.6149, -122.1941, "load", zoom); //The place is Bellevue.
    
    // pin&InfoBoxを挿入
    //* 配列の長さを取得
    let len = lat.length;
    //* forループで配列の数だけ処理をする
    for(let i=0; i<len; i++){
        //* 最初にpin,次にinfoboxHtml
        map.pin(lat[i],lon[i],"#193670");
        let h = '<div style="background:rgba(178,34,34,0.7); color:white;">name[i]<br><img src="upload/'+img[i]+'"width="120"></div>';
        map.infoboxHtml(lat[i], lon[i], h);
    }

    //


    //* map.changeMapを使って最初の座標を中心に表示
    map.changeMap(lat[len-10],lon[len-10],"load",zoom);



    
}
</script>
</body>
</html>



