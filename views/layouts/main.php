<?php

/** @var \yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => 'QL Phòng Khám Tư Nhân',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    
    // --- CẤU HÌNH MENU CHÍNH ---
    $menuItems = [
        ['label' => 'Trang Chủ', 'url' => ['/site/index']],
        
        // Nhóm Quản Lý Chính (Động)
        [
            'label' => 'Nghiệp Vụ Chính',
            'items' => [
                ['label' => 'Quản Lý Bệnh Nhân', 'url' => ['/benh-nhan/index']],
                ['label' => 'Quản Lý Lần Khám', 'url' => ['/kham-benh/index']],
                ['label' => 'Quản Lý Đợt Điều Trị', 'url' => ['/dieu-tri/index']], 
                ['label' => 'Quản Lý Hóa Đơn', 'url' => ['/thanh-toan/index']],
            ],
            'options' => ['class' => 'dropdown'],
        ],

        // ✅ DANH MỤC (Tĩnh/CRUD đơn giản) - SỬ DỤNG DanhMucController
        [
            'label' => 'Danh Mục',
            'items' => [
                // Nhân viên vẫn dùng controller riêng vì có logic phức tạp hơn (hệ số lương, vai trò)
                ['label' => 'Danh Mục Nhân Viên', 'url' => ['/nhan-vien/index']], 
                
                // Các mục đã gộp vào DanhMucController
                ['label' => 'Danh Mục Bệnh', 'url' => ['/danh-muc/index', 'type' => 'benh']],
                ['label' => 'Danh Mục Thuốc', 'url' => ['/danh-muc/index', 'type' => 'thuoc']],
                ['label' => 'Danh Mục CSVC', 'url' => ['/danh-muc/index', 'type' => 'csvc']],
            ],
            'options' => ['class' => 'dropdown'],
        ],


        // --- BÁO CÁO - THỐNG KÊ ---
        [
            'label' => 'Báo Cáo - Thống Kê',
            'items' => [
                ['label' => 'Thống Kê Lần Khám', 'url' => ['bao-cao/thong-ke-lan-kham']], 
                ['label' => 'Doanh Thu Chi Tiết', 'url' => ['bao-cao/doanh-thu-thang']], 
                ['label' => 'Thống Kê Bệnh Phổ Biến', 'url' => ['bao-cao/benh-pho-bien']],
                ['label' => 'Bảng Lương Nhân Viên', 'url' => ['bao-cao/bang-luong-chi-tiet']],
                ['label' => 'Tình Trạng Điều Trị', 'url' => ['bao-cao/tinh-trang-dieu-tri']],
            ],
            'options' => ['class' => 'dropdown'], 
        ],
        // ----------------------------------------------------
    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Đăng Nhập', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
            . Html::submitButton(
                'Đăng Xuất (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ml-auto'],
        'items' => $menuItems,
    ]);
    
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left">&copy; Phòng Khám Tư Nhân <?= date('Y') ?></p>
        <p class="float-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>