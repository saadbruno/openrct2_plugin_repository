<?php
function getPluginList($page = 1, $items = 8, $sort = 'new', $order = 'desc', $owner ='')
{
    // if there's a sort get, we override the one sent through the function
    if ($_GET['sort']) {
        $sort = $_GET['sort'];
    }
    // if there's an order get, we override the one sent through the function
    if ($_GET['order']) {
        $order = $_GET['order'];
    }

    global $pdo;
    // creates plugins array
    $plugins = [];

    // ==== PAGINATION =====
    // get number of total items
    // this changes the query and execution based if we selected a specific user or not
    $query = "SELECT ";
    if (!empty($owner)) { $query .= "`plugins`.`owner`,"; }
    $query .= "COUNT(*) AS `n` FROM `plugins` ";
    if (!empty($owner)) { $query .= "WHERE `plugins`.`owner` = ? GROUP BY `plugins`.`owner` "; }

    $stmt_count = $pdo->prepare($query); 

    if (!empty($owner)) {
        $stmt_count->execute([$owner]);
    } else {
        $stmt_count->execute();
    }
    

    $count = $stmt_count->fetch();

    // get total number of pages
    $pages = ceil($count['n'] / $items);
    // if user requested a page higher than total of pages, we override it
    $page = $page > $pages ? $pages : $page;
    // starting index of the query
    $start = ($page - 1) * $items;
    // adds number of pages to array
    $plugins['info']['pages'] = $pages;

    // ====== END PAGINATION =====

    // sorting
    $sortQuery = '';
    switch ($sort) {
        case 'rating':
            $sortQuery = 'stargazers';
            break;
        case 'name':
            $sortQuery = 'name';
            break;
        case 'new':
        default:
            $sortQuery = 'submittedAt';
            break;
    }

    $orderQuery = '';
    switch ($order) {
        case 'asc':
            $orderQuery = 'ASC';
            break;
        case 'desc':
        default:
            $orderQuery = 'DESC';
            break;
    }

    // build the query
    $query = "SELECT `plugins`.`id`,`plugins`.`name`,`plugins`.`description`,`plugins`.`submittedAt`,`plugins`.`updatedAt`,`plugins`.`usesCustomOpenGraphImage`,`plugins`.`thumbnail`,`plugins`.`stargazers`,`plugins`.`owner`,`plugins`.`licenseName`, `users`.`username`, `users`.`avatarUrl` ";
    $query .= "FROM `plugins` ";
    $query .= "LEFT JOIN `users` ";
    $query .= "ON `plugins`.`owner` = `users`.`id` ";
    // if we select a specific owner, add this to the query
    if (!empty($owner)) {
        $query .= "WHERE `plugins`.`owner` = ? ";
    }
    $query .= "ORDER BY $sortQuery $orderQuery "; // (This is not user input, so we should be fine) i know you shouldn't put variables directly in the statement. But it doesn't seem to work putting it in the execute() for the ORDER BY. 
    $query .= "LIMIT ?,? ";

    // get plugins
    $stmt_plugins = $pdo->prepare($query); 
    if (!empty($owner)) {
        $stmt_plugins->execute([$owner,$start,$items]);
    } else {
        $stmt_plugins->execute([$start,$items]);
    }
    

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

    debug($plugins, "DB query result");

    return $plugins;
}

function getPluginDetails($id) {

    global $pdo;

    // build the query
    $query = "SELECT `plugins`.`id`,`plugins`.`name`,`plugins`.`description`,`plugins`.`submittedAt`,`plugins`.`updatedAt`,`plugins`.`usesCustomOpenGraphImage`,`plugins`.`thumbnail`,`plugins`.`stargazers`,`plugins`.`readme`,`plugins`.`owner`,`plugins`.`url`,`plugins`.`licenseName`,`plugins`.`licenseUrl`, `users`.`username`, `users`.`avatarUrl`, `users`.`url` AS `ownerUrl` ";
    $query .= "FROM `plugins` ";
    $query .= "LEFT JOIN `users` ";
    $query .= "ON `plugins`.`owner` = `users`.`id` ";
    $query .= "WHERE `plugins`.`id` = ? ";
    $query .= "LIMIT 1";

    // get plugin info
    $stmt_plugin = $pdo->prepare($query); 
    $stmt_plugin->execute([$id]);
    $row_plugin = $stmt_plugin->fetch();
    

    // get plugin tags
    $stmt_tags = $pdo->prepare("SELECT `tag` FROM `tags` WHERE `plugin_id` = ? ORDER BY `tags`.`tag` ASC");
    $stmt_tags->execute([$id]);
    $tags = $stmt_tags->fetchAll();

    // sets relative dates for updated and submitted
    $row_plugin['submittedAtRel'] = findTimeAgo(date("Y-m-d H:i:s", $row_plugin['submittedAt']), 'now');
    $row_plugin['submittedAtRelShort'] = findTimeAgo(date("Y-m-d H:i:s", $row_plugin['submittedAt']), 'now', 'short');
    $row_plugin['updatedAtRel'] = findTimeAgo(date("Y-m-d H:i:s", $row_plugin['updatedAt']), 'now');
    $row_plugin['updatedAtRelShort'] = findTimeAgo(date("Y-m-d H:i:s", $row_plugin['updatedAt']), 'now', 'short');


    $plugin = $row_plugin;
    $plugin['tags'] = $tags;


    debug($plugin, "DB query result");

    return $plugin;

}

function searchPlugins($searchQuery,$page = 1, $items = 8, $sort = 'new', $order = 'desc', $owner ='') {
    // To do: Add filtering by author, and other stuff here

    global $pdo;

    $query = "SELECT `id` FROM (
        SELECT `plugins`.`id`,`plugins`.`name`,`plugins`.`description`,`plugins`.`submittedAt`,`plugins`.`owner`,`tags`.`tag`,`users`.`username`
        FROM `plugins` 
        LEFT JOIN `tags` 
        ON `plugins`.`id` = `tags`.`plugin_id` 
        LEFT JOIN `users`
        ON `plugins`.`owner` = `users`.`id`
        WHERE `plugins`.`name` LIKE ?
        OR `plugins`.`description` LIKE ?
        OR `users`.`username` LIKE ?
        OR `tags`.`tag` LIKE ?
        ORDER BY `plugins`.`submittedAt` DESC
        ) AS TEMP
        GROUP BY `TEMP`.`id`";

        $stmt_search = $pdo->prepare($query); 

        $keyword = "%".$searchQuery."%";
        try {
            $stmt_search->execute([$keyword,$keyword,$keyword,$keyword]);
        } catch (Exception $e) {
          die($e);
        }

        $ids_array = $stmt_search->fetchAll();

        $ids = [];
        foreach ($ids_array as $value) {
            array_push($ids, $value['id']);
        }

        // runs the other function that gets the list via IDs
        return listPluginsFromIdArray($ids,$page, $items, $sort, $order, $owner);
}

function listPluginsFromIdArray($ids,$page = 1, $items = 8, $sort = 'new', $order = 'desc', $owner ='') {

        debug($ids, 'Listing plugins with these IDs:');

        // if there are no IDs, we return an empty array
        if (empty($ids)) {
            return $plugins = [];
        }

        // if there's a sort get, we override the one sent through the function
        if ($_GET['sort']) {
            $sort = $_GET['sort'];
        }
        // if there's an order get, we override the one sent through the function
        if ($_GET['order']) {
            $order = $_GET['order'];
        }
    
        global $pdo;
        // creates plugins array
        $plugins = [];
    
        // ==== PAGINATION =====
        // get number of total items
        // this changes the query and execution based if we selected a specific user or not
        $query = "SELECT ";
        $query .= "COUNT(*) AS `n` FROM `plugins` ";
        $query .= "WHERE `plugins`.`id` = 'dummy' ";
        foreach ($ids as $id) {
            $query .= "OR `plugins`.`id` = ? ";
        }
    
        $stmt_count = $pdo->prepare($query); 
        $stmt_count->execute($ids);
        
    
        $count = $stmt_count->fetch();
    
        // get total number of pages
        $pages = ceil($count['n'] / $items);
        // if user requested a page higher than total of pages, we override it
        $page = $page > $pages ? $pages : $page;
        // starting index of the query
        $start = ($page - 1) * $items;
        // adds number of pages to array
        $plugins['info']['pages'] = $pages;
    
        // ====== END PAGINATION =====
    
        // sorting
        $sortQuery = '';
        switch ($sort) {
            case 'rating':
                $sortQuery = 'stargazers';
                break;
            case 'name':
                $sortQuery = 'name';
                break;
            case 'new':
            default:
                $sortQuery = 'submittedAt';
                break;
        }
    
        $orderQuery = '';
        switch ($order) {
            case 'asc':
                $orderQuery = 'ASC';
                break;
            case 'desc':
            default:
                $orderQuery = 'DESC';
                break;
        }
    


    global $pdo;

    $query = "SELECT `plugins`.`id`,`plugins`.`name`,`plugins`.`description`,`plugins`.`submittedAt`,`plugins`.`updatedAt`,`plugins`.`usesCustomOpenGraphImage`,`plugins`.`thumbnail`,`plugins`.`stargazers`,`plugins`.`owner`,`plugins`.`licenseName`, `users`.`username`, `users`.`avatarUrl` ";
    $query .= "FROM `plugins` ";
    $query .= "LEFT JOIN `users` ";
    $query .= "ON `plugins`.`owner` = `users`.`id` ";
    $query .= "WHERE `plugins`.`id` = 'dummy' ";
    foreach ($ids as $id) {
        $query .= "OR `plugins`.`id` = ? ";
    }
    $query .= "ORDER BY $sortQuery $orderQuery ";
    $query .= "LIMIT ?,? ";
    

    // get plugins
    $stmt_list = $pdo->prepare($query); 
    if (!empty($owner)) { // this is broken for now
        $args = [$owner];
        array_merge($args, $ids);
        $stmt_list->execute($args);
    } else {
        $args = $ids;
        array_push($args,$start,$items);
        $stmt_list->execute($args);
    }

    while ($row_plugins = $stmt_list->fetch()) {

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