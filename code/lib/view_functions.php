<?php

// PAGINATION

function drawPagination($curr_page = 1, $total_pages, $query = [], $compact = false) {
    // if requested page is higher than the total number of pages, we override it
    $curr_page = $curr_page > $total_pages ? $total_pages : $curr_page;

    // keep all query strings when switching pages (except for q1 to q3, and p)
    $query = array_merge($query, $_GET);
    unset($query['p'], $query['q1'], $query['q2'], $query['q3']);
    // query string. THis will be used, in the future, to keep other query strings while changing pages (things like sorting and so on)
    $queryString = http_build_query($query);

    ?>
    <nav aria-label="Pagination for newest plug-ins">
        <ul class="pagination pagination-sm justify-content-center">

            <li class="page-item <?= !$curr_page || $curr_page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="/list/?<?=$queryString?>&p=<?= $curr_page - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <?php 
            // first page
            // if we only have one page, we don't need this
            if ($total_pages > 1) {
            ?>
            <li class="page-item  <?= !$curr_page || $curr_page == 1 ? 'active' : '' ?>"><a class="page-link" href="/list/?<?=$queryString?>&p=1">1</a></li>
            <?php
            }
            
            // ellipsis button
            if (($compact == true && $curr_page >= 4) || ($compact == false && $curr_page >= 5)) {
            ?>
                <li class="page-item disabled"><a class="page-link" href="#">&hellip;</a></li>
            <?php
            }
            // page - 2
            if ($curr_page - 2 > 1 && $compact == false) {
            ?>
                <li class="page-item"><a class="page-link" href="/list/?<?=$queryString?>&p=<?= $curr_page - 2 ?>"><?= $curr_page - 2 ?></a></li>
            <?php
            }
            // page - 1
            if ($curr_page - 1 > 1) {
            ?>
                <li class="page-item"><a class="page-link" href="/list/?<?=$queryString?>&p=<?= $curr_page - 1 ?>"><?= $curr_page - 1 ?></a></li>
            <?php
            }
            // current page
            if ($curr_page > 1 && $curr_page < $total_pages) {
            ?>
                <li class="page-item active"><a class="page-link" href="#"><?= $curr_page ?></a></li>
            <?php
            }
            // page + 1
            if ($curr_page + 1 < $total_pages) {
            ?>
                <li class="page-item"><a class="page-link" href="/list/?<?=$queryString?>&p=<?= $curr_page + 1 ?>"><?= $curr_page + 1 ?></a></li>
            <?php
            }
            // page + 2
            if ($curr_page + 2 < $total_pages && $compact == false) {
            ?>
                <li class="page-item"><a class="page-link" href="/list/?<?=$queryString?>&p=<?= $curr_page + 2 ?>"><?= $curr_page + 2 ?></a></li>
            <?php
            }
            // ellipsis button
            if (($compact == true && $curr_page <= $total_pages - 3) || ($compact == false && $curr_page <= $total_pages - 4)) {
            ?>
                <li class="page-item disabled"><a class="page-link" href="#">&hellip;</a></li>
            <?php } ?>

            <li class="page-item <?= $curr_page >= $total_pages ? 'active' : '' ?>"><a class="page-link" href="/list/?<?=$queryString?>&p=<?= $total_pages ?>"><?= $total_pages ?></a></li>

            <li class="page-item <?= $curr_page >= $total_pages ? 'disabled' : '' ?>">
                <a class="page-link" href="/list/?<?=$queryString?>&p=<?= $curr_page + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
    <?php
}

function drawList($list, $title = 'List', $usePagination = true, $viewMore = '', $curr_page='1', $show_results_select = true, $results_select_value = 8) {
    ?>
    <div class="col-12 list-container">
        <div class="row align-items-center">
            <div class="col">
                <h2><?= $title ?></h2>
            </div>
            <?php if($show_results_select) { ?>
            <div class="col-12 col-md-auto">
                Results per page: 
                <select name="results" id="results-select">
                    <?php if(isset($_GET['results'])) { ?>
                    <option value="<?= intval($_GET['results']) ?>"><?= intval($_GET['results']) ?></option>
                    <option value="" disabled>___</option>
                    <?php } ?>
                    <option value="8">8</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <?php } ?>
        </div>
        <div class="plugin-list">
            <?php
            // if for some reason the list is empty (mysql error, or empty seach), we show a feedback message, instead of an error
            if (!empty($list['data'])) {
                foreach ($list['data'] as $key => $plugin) {
                    include "views/home/partials/list-item.php";
                }
            } else {
                echo '<div class="my-3">No results</div>';
            }

            ?>

        </div>
    <?php 
    if ($usePagination == true && !empty($list['data'])) {
        drawPagination($curr_page, $list['info']['pages']);
    } else if ($usePagination == false && !empty($viewMore)) {
    ?>
        <div class="footer view-more">
            <a href="/list/?sort=<?= $viewMore ?>">View more <i class="fas fa-arrow-right"></i></a>
        </div>
    <?php
    }
    
    ?>
    </div>
    <?php
}