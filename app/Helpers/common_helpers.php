<?php

function paginateData($query, $page, $perPage, $totalItems)
{
    $offset = ($page - 1) * $perPage;
    $totalPages = ceil($totalItems / $perPage);
    
    $meta = [
        'current_page' => $page,
        'per_page' => $perPage,
        // 'total_items' => $totalItems,
        'total_pages' => $totalPages,
    ];

    $models = $query->skip($offset)
                    ->take($perPage)
                    ->get();
    return  [
                "data" => $models,
                "meta" => $meta
            ];
}