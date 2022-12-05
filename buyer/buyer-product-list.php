<?php
require_once("../db2-connect.php");

session_start();
if (!isset($_SESSION["user"])) {
  // header("location: login.php");
}
$category = "";
$sqlCategory = "SELECT * FROM category  ORDER BY id ASC";
$resultCategory = $conn->query($sqlCategory);
$rowsCategory = $resultCategory->fetch_all(MYSQLI_ASSOC);





// 頁數


if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = 1;
}
$per_page = 10;
$page_start = ($page - 1) * $per_page;
// 作者查詢
// if (isset($_GET["author"])) {
//   $author = $_GET["author"];
//   $sql2 = "SELECT * FROM `product` WHERE `product_valid`=1 AND `product`.`author` LIKE '%$author%'  ORDER BY `product`.`create_time` DESC LIMIT $page_start, $per_page";
//   $sqlAll = "SELECT * FROM `product` WHERE `product_valid`=1 AND `product`.`author` LIKE '%$author%'  ORDER BY `product`.`create_time` DESC";

//   $result = $conn->query($sql2);
//   $resultAll = $conn->query($sqlAll);
//   $userCount = $resultAll->num_rows;

// 分類頁面ＳＱＬ
// }elseif (isset($_GET["category"])) {
if (isset($_GET["category"])) {
  $pageCategory = $_GET["category"];



  $sql2 = "SELECT * FROM `product` WHERE `product_valid`=1 AND `category` =  " . $_GET["category"] . " ORDER BY `product`.`create_time` DESC LIMIT $page_start, $per_page";
  $sqlAll = "SELECT * FROM `product` WHERE `product_valid`=1 AND `category` =  " . $_GET["category"] . " ORDER BY `product`.`create_time` DESC";


  $result = $conn->query($sql2);
  $resultAll = $conn->query($sqlAll);
  $userCount = $resultAll->num_rows;


  // 價錢分類
} elseif (isset($_GET["min"])) {
  $min = $_GET["min"];
  $max = $_GET["max"];
  $category_radio = $_GET["category_radio"];

  if (empty($min)) $min = 0;
  if (empty($max)) $max = 99999;
  
  // 多項篩選
  if (!empty($category_radio)) {

    $sql2 = "SELECT * FROM `product` WHERE `product_valid`=1 AND `category` =  " . $_GET["category_radio"] . " AND product.price >= $min AND product.price <=$max ORDER BY product.price DESC
    LIMIT $page_start, $per_page";

    $sqlAll = "SELECT * FROM `product` WHERE `product_valid`=1 AND `category` =  " . $_GET["category_radio"] . " AND product.price >= $min AND product.price <=$max ORDER BY product.price DESC";

  } else {
    $sql2 = "SELECT * FROM `product` WHERE `product_valid`=1 AND product.price >= $min AND product.price <=$max ORDER BY product.price DESC
    LIMIT $page_start, $per_page";
    $sqlAll = "SELECT * FROM `product` WHERE `product_valid`=1 AND product.price >= $min AND product.price <=$max";
  }
  // var_dump($sqlAll);
  // exit;
  $result = $conn->query($sql2);
  $resultAll = $conn->query($sqlAll);
  $userCount = $resultAll->num_rows;


  //全部
} else {
  $sql2 = "SELECT * FROM `product` WHERE `product_valid`=1 ORDER BY `product`.`create_time` DESC
  LIMIT $page_start, $per_page";
  $sqlAll = "SELECT * FROM `product` WHERE `product_valid`=1 ORDER BY `product`.`id` ASC ";
  $result = $conn->query($sql2);
  $resultAll = $conn->query($sqlAll);
  $userCount = $resultAll->num_rows;
}
// 
$rows = $result->fetch_all(MYSQLI_ASSOC);
$productCount = $resultAll->num_rows;


//計算頁數
$totalPage = ceil($userCount / $per_page);



?>
<!doctype html>
<html lang="en">

<head>
  <title>Buyer-product-list</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link rel="stylesheet" href="/fontawesome-free-6.2.0-web/css/all.min.css">
  <style>
    body {
      height: 300vh;
    }

    a {
      text-decoration: none;
      color: #444;
    }

    :root {
      --side-width: 260px;
    }

    .main-nav .form-control {
      background: #444;
      border: none;
      color: #fff;
      border-radius: 0;
    }

    .main-nav .btn {
      border-radius: 0;
    }

    .nav a {
      color: gray;
    }

    .nav a:hover {
      color: white;
    }

    .logo {
      width: var(--side-width);
    }

    .left-aside {
      width: var(--side-width);
      height: 100vh;
      padding-top: 54px;
      overflow: auto;
    }

    .aside-menu ul a {
      display: block;
      color: #666;
      text-decoration: none;
      display: flex;
      justify-content: center;
      margin: 15px;
    }

    .aside-menu a:hover {
      color: white;
      background: cadetblue;
      border-radius: 0.375rem;

    }

    .aside-menu a i {
      margin-right: 8px;
      margin-top: 4px;
    }

    .aside-subtitle {
      font-size: 14px;
    }

    .main-content {
      margin-left: calc(var(--side-width) + 20px);
      padding-top: 54px;
    }
  </style>
</head>

<body>
  <nav class="main-nav d-flex bg-dark fixed-top shadow">
    <a class="text-nowrap px-3 text-white text-decoration-none d-flex align-items-center justify-content-center logo flex-shrink-0 fs-4 text" href="">藝拍</a>
    <div class="nav">
      <a class="nav-link" href="#">首頁</a>
      <a class="nav-link" href="../buyer/buyer-product-list.php">藝術品參觀</a>
      <a class="nav-link" href="#">展覽空間</a>
    </div>
    <div class="position-absolute top-0 end-0">
      <a class="btn btn-dark text-nowrap" href="logout.php">Sign out</a>
    </div>
  </nav>
  <aside class="left-aside position-fixed bg-dark border-end">
    <nav class="aside-menu">
      <!-- <div class="pt-2 px-3 pb-2 d-flex justify-content-center text-white">
        Welcome <?= $_SESSION["user"]["account"] ?> !
      </div> -->
      <ul class="list-unstyled">
        <a href="#" class=" align-items-center link-dark text-decoration-none ">
          <img src="./images/a.jpg" alt="" width="110" height="110" class="rounded-circle mx-auto">
        </a>
        <h1 class="py-2 d-flex justify-content-center text-white">會員</h1>
        <hr class="text-white">
        <li class="active"><a href="../buyer/buyer.php?id=<?= $_SESSION["user"]["id"] ?>" class="px-3 py-2"> <i class="fa-solid fa-face-smile fa-fw"></i>會員個人資料</a></li>
        <li><a href="../buyer/buyer-order-detail.php?id=<?= $_SESSION["user"]["id"] ?>" class="px-3 py-2"><i class="fa-regular fa-file-lines fa-fw"></i>個人訂單檢視</a></li>
        <li><a href="" class="px-3 py-2"><i class="fa-solid fa-barcode"></i>折扣卷</a></li>
        <li><a href="" class="px-3 py-2"><i class="fa-solid fa-heart"></i>我的收藏</a></li>
      </ul>

    </nav>
  </aside>
  <main class="main-content">
    <div class="d-flex justify-content-between">
      <h1>主選單</h1>
    </div>
    <div class="">
      <div class="container">

        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link <?php if (!isset($_GET["category"])) echo "active"; ?>" aria-current="page" href="buyer-product-list.php">全部</a>
          </li>
          <!--  -->
          <?php foreach ($rowsCategory as $category) : ?>
            <li class="nav-item">
              <a class="nav-link" <?php if (isset($_GET["category"]) && $_GET["category"] == $category["id"]) echo "active"; ?> href="buyer-product-list.php?category=<?= $category["id"] ?>"> <?= $category["name"] ?> </a>
            </li>
          <?php endforeach; ?>
          <!--  -->
        </ul>
        <div class="py-2">
          <!-- 表單 -->
          <form action="buyer-product-list.php" method="GET">
            <div class="row align-items-center g-2">
              <?php if ((isset($_GET["min"])) || (isset($_GET["max"]))) : ?>
                <div class="col-auto">
                  <a class="btn btn-dark" href="./buyer-product-list.php">Back</a>
                </div>
              <?php endif; ?>
              <div class="row-auto">
                <input type="text" class="form-control text-left" name="author" placeholder="輸入畫家名稱" value="<?php if (isset($_GET["author"])) echo $_GET["author"]; ?>">
              </div>
              <div class="col-auto">
                <input type="number" class="form-control text-center" name="min" placeholder="輸入最小金額" value="<?php if (isset($_GET["min"])) echo $_GET["min"]; ?>">
              </div>
              <div class="col-auto">
                ~
              </div>
              <div class="col-auto">
                <input type="number" class="form-control text-center" name="max" placeholder="輸入最大金額" value="<?php if (isset($_GET["max"])) echo $_GET["max"]; ?>">
              </div>
              <div class="row-auto">
                <input class="form-check-input" type="radio" name="category_radio" id="category-radio1" value="1" <?php if(isset($_GET["category_radio"]) && (($_GET["category_radio"])=="1")) echo "checked"; ?>>
                <label class="form-check-label" for="category-radio1">水墨</label>


                <input class="form-check-input" type="radio" name="category_radio" id="category-radio2" value="2" <?php if(isset($_GET["category_radio"]) && (($_GET["category_radio"])=="2")) echo "checked"; ?>>
                <label class="form-check-label" for="category-radio2">膠彩</label>


                <input class="form-check-input" type="radio" name="category_radio" id="category-radio3" value="3" <?php if(isset($_GET["category_radio"]) && (($_GET["category_radio"])=="3")) echo "checked"; ?>>
                <label class="form-check-label" for="category-radio3">油畫</label>


                <input class="form-check-input" type="radio" name="category_radio" id="category-radio4" value="4" <?php if(isset($_GET["category_radio"]) && (($_GET["category_radio"])=="4")) echo "checked"; ?>>
                <label class="form-check-label" for="category-radio4">水彩</label>


                <input class="form-check-input" type="radio" name="category_radio" id="category-radio5" value="5" <?php if(isset($_GET["category_radio"]) && (($_GET["category_radio"])=="5")) echo "checked"; ?>>
                <label class="form-check-label" for="category-radio5">版畫</label>


                <input class="form-check-input" type="radio" name="category_radio" id="category-radio6" value="6" <?php if(isset($_GET["category_radio"]) && (($_GET["category_radio"])=="6")) echo "checked"; ?>>
                <label class="form-check-label" for="category-radio6">電繪</label>
              </div>
              <div class="row-auto">
                <button class="btn btn-dark" type="submit">篩選</button>
              </div>
            </div>
          </form>
          <?php ?>
        </div>
        <div class="py-2 text-end">
          共<?= $productCount ?>項
        </div>
        <?php if ($userCount > 0) : ?>
          <div class="row d-flex flex-wrap">
            <?php foreach ($rows as $row) :
            ?>

              <div class="col-lg-3 col-md-6 py-3">
                <div class="card position-relative">
                  <a class="like position-absolute"></a>
                  <figure class="ratio ratio-16x9">
                    <img class="object-cover" src="../product/images/<?= $row["images"] ?>" alt="">
                  </figure>
                  <div class="px-2 pb-3">
                    <div class="pb-2 text-primary">
                      <a href="buyer-product-list.php?category=<?= $row["category"] ?>">
                        <?php
                        if ($row["category"] == "1") {
                          echo "水墨";
                        } elseif ($row["category"] == "2") {
                          echo "膠彩";
                        } elseif ($row["category"] == "3") {
                          echo "油畫";
                        } elseif ($row["category"] == "4") {
                          echo "水彩";
                        } elseif ($row["category"] == "5") {
                          echo "版畫";
                        } elseif ($row["category"] == "6") {
                          echo "電繪";
                        }
                        ?>

                      </a>
                    </div>
                    <h3 class="text-center h4">
                      <?= $row["name"] ?>
                    </h3>
                    <div class="text-end">
                      <?= $row["price"] ?>
                    </div>
                  </div>
                </div>
              </div>

            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
      <!-- 頁碼選單 -->
      <?php if (isset($_GET["category"])) : ?>
        <nav aria-label="Page navigation example">
          <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
              <li class="page-item 
            <?php
              if ($i == $page) echo "active";
            ?>">
                <a class="page-link" href="buyer-product-list.php?category=<?= $_GET["category"] ?>&page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
      <!-- 價格篩選 -->
      <?php elseif ((isset($_GET["min"])) && (isset($_GET["max"]))) : ?>
        <!-- 多項篩選 -->
        <?php if(isset($_GET["category_radio"])) : ?>
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
                <li class="page-item 
              <?php
                if ($i == $page) echo "active";
              ?>">
                  <a class="page-link" href="buyer-product-list.php?min=<?= $_GET["min"] ?>&max=<?= $_GET["max"] ?>&category_radio=<?= $_GET["category_radio"] ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php else : ?>
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
                <li class="page-item 
              <?php
                if ($i == $page) echo "active";
              ?>">
                  <a class="page-link" href="buyer-product-list.php?min=<?= $_GET["min"] ?>&max=<?= $_GET["max"] ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php endif; ?>
        <?php elseif (isset($_GET["category_radio"])) : ?>
        <!-- 多項篩選 -->
        <?php if((isset($_GET["min"])) && (isset($_GET["max"]))) : ?>
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
                <li class="page-item 
              <?php
                if ($i == $page) echo "active";
              ?>">
                  <a class="page-link" href="buyer-product-list.php?min=<?= $_GET["min"] ?>&max=<?= $_GET["max"] ?>&category_radio=<?= $_GET["category_radio"] ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php else : ?>
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
                <li class="page-item 
              <?php
                if ($i == $page) echo "active";
              ?>">
                  <a class="page-link" href="buyer-product-list.php?category_radio=<?= $_GET["category_radio"] ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php endif ?>
        
      <?php else : ?>
        <nav aria-label="Page navigation example">
          <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
              <li class="page-item 
            <?php
              if ($i == $page) echo "active";
            ?>">
                <a class="page-link" href="buyer-product-list.php?page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
      <?php endif; ?>

    </div>
    </div>
    </div>

  </main>

  <!-- <footer class="footer">
      <div class="container-fruid d-flex justify-content-center">
        <div class="menu list-unstyled inline-flex">

          <a href="#" class="text-decoration-none text-white-50 px-2">關於 藝拍</a>

          <a href="#" class="text-decoration-none text-white-50 px-2">隱私權政策</a>

          <a href="#" class="text-decoration-none text-white-50 px-2">聯絡我們</a>
        </div>

      </div>
      <div class="d-flex justify-content-center">
        <div class="menu list-unstyled inline-flex py-2">
          <a href="" class="text-decoration-none text-white-50">E𝝅 © All Rights Reserved.</a>
        </div>

      </div>


    </footer> -->

</body>

</html>