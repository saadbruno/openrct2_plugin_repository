<?php
function drawPagination($curr_page = 1, $total_pages, $query = [], $compact = false)
{
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

            <li class="page-item  <?= !$curr_page || $curr_page == 1 ? 'active' : '' ?>"><a class="page-link" href="/list/?<?=$queryString?>&p=1">1</a></li>

            <?php
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
