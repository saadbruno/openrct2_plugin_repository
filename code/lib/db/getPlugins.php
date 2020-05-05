<?php
function getPluginList($page = 1, $items = 8, $sort = 'submitted', $order = 'desc')
{

    global $pdo;
    // creates plugins array
    $plugins = [];

    // ==== PAGINATION =====
    // get number of total items
    $count = $pdo->query("SELECT COUNT(*) AS `n` FROM `plugins`")->fetch();
    // get total number of pages
    $pages = ceil($count['n'] / $items);
    // if user requested a page higher than total of pages, we override it
    $page = $page > $pages ? $pages : $page;
    // starting index of the query
    $start = ($page - 1) * $items;
    // adds number of pages to array
    $plugins['info']['pages'] = $pages;

    // ====== END PAGINATION =====

    // get plugins
    $stmt_plugins = $pdo->prepare("SELECT `plugins`.`id`,`plugins`.`name`,`plugins`.`description`,`plugins`.`submittedAt`,`plugins`.`updatedAt`,`plugins`.`usesCustomOpenGraphImage`,`plugins`.`thumbnail`,`plugins`.`stargazers`,`plugins`.`owner`, `users`.`username`, `users`.`avatarUrl`
                                FROM `plugins`
                                LEFT JOIN `users`
                                ON `plugins`.`owner` = `users`.`id`
                                ORDER BY `plugins`.`submittedAt` DESC
                                LIMIT ?,?");
    $stmt_plugins->execute([$start, $items]);

    while ($row_plugins = $stmt_plugins->fetch()) {

        // get plugin tags
        $stmt_tags = $pdo->prepare("SELECT `tag` FROM `tags` WHERE `plugin_id` = ? ORDER BY `tags`.`tag` ASC");
        $stmt_tags->execute([$row_plugins['id']]);
        $tags = $stmt_tags->fetchAll();

        // sets relative dates for updated and submitted
        $row_plugins['submittedAtRel'] = findTimeAgo(date("Y-m-d H:i:s", $row_plugins['submittedAt']), 'now');
        $row_plugins['submittedAtRelShort'] = findTimeAgo(date("Y-m-d H:i:s", $row_plugins['submittedAt']), 'now', 'short');
        $row_plugins['updatedAtRel'] = findTimeAgo(date("Y-m-d H:i:s", $row_plugins['updatedAt']), 'now');
        $row_plugins['updatedAtRelShort'] = findTimeAgo(date("Y-m-d H:i:s", $row_plugins['updatedAt']), 'now', 'short');


        $plugins['data'][$row_plugins['id']] = $row_plugins;
        $plugins['data'][$row_plugins['id']]['tags'] = $tags;

    }

    return $plugins;
}
