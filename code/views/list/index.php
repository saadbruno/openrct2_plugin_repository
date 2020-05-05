<!doctype html>
<html lang="en">

<?php include_once "views/home/partials/head.php"; ?>

<body>

    <?php
    include_once "views/home/partials/nav.php";
    ?>

    <main role="main" class="container">

        <div class="row">
            <?php
            drawList($list, $list['info']['title'], true, '', $p);
            ?>

        </div>

    </main>


    <?php
    include_once "views/home/partials/footer.php";
    include_once "views/home/partials/bottom.php";
    ?>

</html>