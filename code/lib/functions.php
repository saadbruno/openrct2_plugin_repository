<?php

// query graphql, from https://gist.github.com/dunglas/05d901cb7560d2667d999875322e690a

function graphql_query(string $endpoint, string $query, array $variables = [], ?string $token = null): array
{
    $headers = ['Content-Type: application/json', 'User-Agent: Dunglas\'s minimal GraphQL client'];
    if (null !== $token) {
        $headers[] = "Authorization: bearer $token";
    }

    if (false === $data = @file_get_contents($endpoint, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => json_encode(['query' => $query, 'variables' => $variables]),
        ]
    ]))) {
        $error = error_get_last();
        throw new \ErrorException($error['message'], $error['type']);
    }

    return json_decode($data, true);
}

function findTimeAgo($past, $now = "now", $length = "long")
{
    // sets the default timezone if required
    // list of supported timezone identifiers
    // http://php.net/manual/en/timezones.php
    // date_default_timezone_set("Asia/Calcutta");
    $secondsPerMinute = 60;
    $secondsPerHour = 3600;
    $secondsPerDay = 86400;
    $secondsPerMonth = 2592000;
    $secondsPerYear = 31104000;
    // finds the past in datetime
    $past = strtotime($past);
    // finds the current datetime
    $now = strtotime($now);

    // creates the "time ago" string. This always starts with an "about..."
    $timeAgo = "";

    // finds the time difference
    $timeDifference = $now - $past;

    // less than 29secs
    if ($timeDifference <= 29) {
        if ($length == "short") {
            $timeAgo = "now";
        } else {
            $timeAgo = "less than a minute";
        }
    }
    // more than 29secs and less than 1min29secss
    else if ($timeDifference > 29 && $timeDifference <= 89) {
        if ($length == "short") {
            $timeAgo = "1 m";
        } else {
            $timeAgo = "1 minute";
        }
    }
    // between 1min30secs and 44mins29secs
    else if (
        $timeDifference > 89 &&
        $timeDifference <= (($secondsPerMinute * 44) + 29)
    ) {
        $minutes = floor($timeDifference / $secondsPerMinute);
        if ($length == "short") {
            $timeAgo = $minutes . " m";
        } else {
            $timeAgo = $minutes . " minutes";
        }
    }
    // between 44mins30secs and 1hour29mins29secs
    else if (
        $timeDifference > (($secondsPerMinute * 44) + 29)
        &&
        $timeDifference < (($secondsPerMinute * 89) + 29)
    ) {
        if ($length == "short") {
            $timeAgo = "1 h";
        } else {
            $timeAgo = "about 1 hour";
        }
    }
    // between 1hour29mins30secs and 23hours59mins29secs
    else if (
        $timeDifference > (
            ($secondsPerMinute * 89) +
            29)
        &&
        $timeDifference <= (
            ($secondsPerHour * 23) +
            ($secondsPerMinute * 59) +
            29)
    ) {
        $hours = floor($timeDifference / $secondsPerHour);
        if ($length == "short") {
            $timeAgo = $hours . " h";
        } else {
            $timeAgo = $hours . " hours";
        }
    }
    // between 23hours59mins30secs and 47hours59mins29secs
    else if (
        $timeDifference > (
            ($secondsPerHour * 23) +
            ($secondsPerMinute * 59) +
            29)
        &&
        $timeDifference <= (
            ($secondsPerHour * 47) +
            ($secondsPerMinute * 59) +
            29)
    ) {
        if ($length == "short") {
            $timeAgo = "1 d";
        } else {
            $timeAgo = "1 day";
        }
    }
    // between 47hours59mins30secs and 29days23hours59mins29secs
    else if (
        $timeDifference > (
            ($secondsPerHour * 47) +
            ($secondsPerMinute * 59) +
            29)
        &&
        $timeDifference <= (
            ($secondsPerDay * 29) +
            ($secondsPerHour * 23) +
            ($secondsPerMinute * 59) +
            29)
    ) {
        $days = floor($timeDifference / $secondsPerDay);
        if ($length == "short") {
            $timeAgo = $days . " d";
        } else {
            $timeAgo = $days . " days";
        }
    }
    // between 29days23hours59mins30secs and 59days23hours59mins29secs
    else if (
        $timeDifference > (
            ($secondsPerDay * 29) +
            ($secondsPerHour * 23) +
            ($secondsPerMinute * 59) +
            29)
        &&
        $timeDifference <= (
            ($secondsPerDay * 59) +
            ($secondsPerHour * 23) +
            ($secondsPerMinute * 59) +
            29)
    ) {
        if ($length == "short") {
            $timeAgo = "1 mo";
        } else {
            $timeAgo = "about 1 month";
        }
    }
    // between 59days23hours59mins30secs and 1year (minus 1sec)
    else if (
        $timeDifference > (
            ($secondsPerDay * 59) +
            ($secondsPerHour * 23) +
            ($secondsPerMinute * 59) +
            29)
        &&
        $timeDifference < $secondsPerYear
    ) {
        $months = round($timeDifference / $secondsPerMonth);
        // if months is 1, then set it to 2, because we are "past" 1 month
        if ($months == 1) {
            $months = 2;
        }
        if ($length == "short") {
            $timeAgo = $months . " mo";
        } else {
            $timeAgo = $months . " months";
        }
    }
    // between 1year and 2years (minus 1sec)
    else if (
        $timeDifference >= $secondsPerYear
        &&
        $timeDifference < ($secondsPerYear * 2)
    ) {
        if ($length == "short") {
            $timeAgo = "1 y";
        } else {
            $timeAgo = "about 1 year";
        }
    }
    // 2years or more
    else {
        $years = floor($timeDifference / $secondsPerYear);
        if ($length == "short") {
            $timeAgo = $years . " y";
        } else {
            $timeAgo = "over " . $years . " years";
        }
    }

    if ($length == "short") {
        return $timeAgo;
    } else {
        return $timeAgo . " ago";
    }
}
