<?php

namespace App\Services;

class SlugPrepare
{
    public function prepare($title)
    {
        $special = array('!', '?', '.', ',', '/', '#', '%', '*', '(', ')', '[', ']', '+', '-', '_', '@', '$', '^', '&', '<', '>', '|', ':', ';', '"', "'");

        $slug = str_replace($special, "", $title);
        $slug = str_replace(' ', '-', $slug);
        $slug = strtolower($slug);

        return $slug;
    }
}
