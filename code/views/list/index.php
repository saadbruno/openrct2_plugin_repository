<!doctype html>
<html lang="en">

<?php include_once "views/home/partials/head.php"; ?>

<body>

    <?php
    include_once "views/home/partials/nav.php";
    ?>

    <main role="main" class="container">

        <div class="row">
            <div class="col-12 list-container">
                <h2>Newest plug-ins</h2>
                <div class="plugin-list">
                    <?php

                    foreach ($list['data'] as $key => $plugin) {
                        include "views/home/partials/list-item.php";
                    }
                    ?>
                </div>


                <?php drawPagination($p, $list['info']['pages']) ?>



            </div>
        </div>

    </main>


    <?php
    include_once "views/home/partials/footer.php";
    include_once "views/home/partials/bottom.php";
    ?>

</html>