<?php
require_once("../db2-connect.php");

if (isset($_GET["search"])) {
  $search = $_GET["search"];
  $sql = "SELECT * FROM product WHERE `product_status`=1 AND `product`.`name` LIKE '%$search%'  ORDER BY `product`.`id` ASC";
  $result = $conn->query($sql);
  $userCount = $result->num_rows;
} else {
  if (isset($_GET["page"])) {
    $page = $_GET["page"];
  } else {
    $page = 1;
  }

  $sqlAll="SELECT * FROM `product` WHERE `product_status`=1 ORDER BY `product`.`id` ASC ";
  $resultAll = $conn->query($sqlAll);
  $userCount = $resultAll->num_rows;


  $per_page = 10;
  // $page=1;
  $page_start = ($page - 1) * $per_page;


  $sql = "SELECT * FROM `product` WHERE `product_status`=1 ORDER BY `product`.`id` DESC
  LIMIT $page_start, $per_page";



  $result = $conn->query($sql);

  //計算頁數
  $totalPage = ceil($userCount / $per_page);
}


$rows = $result->fetch_all(MYSQLI_ASSOC);


// var_dump($totalPage);
// exit;
?>
<!doctype html>
<html lang="en">

<head>
  <title>products</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link rel="stylesheet" href="/fontawesome-free-6.2.0-web/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <style>
        body{
            height: 300vh;
        }
        :root{
            --side-width: 260px;
        }
        .main-nav .form-control{
            background: #444;
            border: none;
            color: #fff;
            border-radius: 0;
        }
        .main-nav .btn{
            border-radius: 0;
        }
        .nav a{
            color: gray;
        }
        .nav a:hover, .nav a.active{
            color: white;
        }
        .logo{
            width: var(--side-width);
        }
        .left-aside{
            width: var(--side-width);
            height: 100vh; 
            padding-top: 54px;
            overflow: auto;
        }
        .aside-menu ul a{
            display: block;
            color:  #666;
            text-decoration: none;
            display: flex;
            justify-content: center;
            margin: 15px;
        }
        .aside-menu a:hover, .aside-menu li.active a{
            color: white;
            background: cadetblue;
            border-radius: 0.375rem;
        }
        .aside-menu a i{
            margin-right: 8px;
            margin-top: 4px;
        }
        .aside-subtitle{
            font-size: 14px;
        }
        .main-content{
            margin-left: calc(var(--side-width) + 20px);
            padding-top: 54px;
        }
    </style>
</head>


<body>
<nav class="main-nav d-flex bg-dark fixed-top shadow">
    <a class="text-nowrap px-3 text-white text-decoration-none d-flex align-items-center justify-content-center logo flex-shrink-0 fs-4 text" href="">藝拍</a>
    <div class="nav">
      <a class="nav-link" aria-current="page" href="#">首頁</a>
      <a class="nav-link" href="../product/product-list2.php">藝術品</a>
      <a class="nav-link" href="../seller/sellers.php">畫家</a>
      <a class="nav-link active" href="../user/dashboard.php">會員</a>
      <a class="nav-link" href="../product/order-list.php">訂單</a>
      <a class="nav-link" href="../user/product-list2.php">展覽空間</a>
    </div>
    <div class="position-absolute top-0 end-0">
      <a class="btn btn-dark text-nowrap" href="logout.php">Sign out</a>
    </div>
  </nav>
  <aside class="left-aside position-fixed bg-dark border-end">
    <nav class="aside-menu">
        <ul class="list-unstyled">
          <h1 class="py-2 d-flex justify-content-center text-white">會員</h1>
          <hr class="text-white">
            <li ><a href="../user/buyers.php" class="px-3 py-2"> <i class="fa-solid fa-user fa-fw"></i>買家資料列表</a></li>
            <li><a href="../user/sellers.php" class="px-3 py-2"> <i class="fa-solid fa-face-smile fa-fw"></i>賣家資料列表</a></li>               
            <li class="active"><a href="../user/products.php" class="px-3 py-2"><i class="fa-regular fa-file-lines fa-fw"></i>藝術品列表</a></li>
            <li><a href="../user/order-list.php" class="px-3 py-2"><i class="fa-solid fa-heart"></i>訂單列表</a></li>
        </ul>
        
    </nav>
  </aside>
  <main class="main-content">
    <div class="d-flex justify-content-between">

    <div class="container">
      <div class="py-5 d-flex justify-content-end">
        <a class="btn btn-dark" href="./file-upload.php">新增商品</a>
      </div>
      <div class="py-2">
        <form action="products.php" method="get">
          <div class="input-group">
            <input type="text" class="form-control" name="search">
            <button type="submit" class="btn btn-dark">作品名稱搜尋</button>
          </div>
        </form>
      </div>
      <?php if (isset($_GET["search"])) : ?>
        <div class="py-2">
          <a class="btn btn-dark" href="products.php">回商品列表</a>
        </div>
        <h1><?= $_GET["search"] ?> 的搜尋結果</h1>
      <?php endif; ?>
      <div class="py-2">
        共 <?= $userCount ?> 張
      </div>
      <?php //var_dump($rows) 
      ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <td><input class="form-check-input" type="checkbox">全選</td>
            <th>id</th>
            <th>作品名稱</th>
            <th>分類</th>
            <th>價錢</th>
            <th>數量</th>
            <th>上傳時間</th>
            <th>作者</th>
            <th>畫作</th>
            <th>簡介</th>
            <th>賣家</th>
          </tr>
        </thead>
        <?php if ($userCount > 0) : ?>
          <tbody>
            <?php foreach ($rows as $row) : ?>
              <tr>
                <td><input class="form-check-input" type="checkbox"></td>
                <td><?= $row["id"] ?></td>
                <td><?= $row["name"] ?></td>
                <td>
                  <?php if($row["category"]=="1") {
                      echo "水墨";
                  } elseif ($row["category"]=="2"){
                      echo "膠彩";
                  }elseif ($row["category"]=="3"){
                      echo "油畫";
                  }elseif ($row["category"]=="4"){
                      echo "水彩";
                  }elseif ($row["category"]=="5"){
                      echo "版畫";
                  }elseif ($row["category"]=="6"){
                      echo "電繪";
                  }?>                
                  </td>
                <?php  ?>  
                <td><?= $row["price"] ?></td>
                <td><?= $row["amount"] ?></td>
                <td><?= $row["create_time"] ?></td>
                <td><?= $row["author"] ?></td>
                <td><?= $row["images"] ?></td>
                <td><?= $row["detailed"] ?></td>
                <td><?= $row["sell_id"] ?></td>

                <td>
                  <a class="btn btn-dark" href="../product/product_page.php?id=<?= $row["id"] ?>">檢視</a>
                  <a class="btn btn-danger" href="../product/delete-product2.php?id=<?= $row["id"] ?>">刪除</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        <?php endif; ?>
      </table>
      <?php if (!isset($_GET["search"])) : ?>
        <nav aria-label="Page navigation example">
          <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
              <li class="page-item 
              <?php
                  if ($i == $page) echo "active";
              ?>">
              <a class="page-link" href="products.php?page=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
          </ul>
        </nav>
      <?php endif; ?>
    </div>
</body>

</html>