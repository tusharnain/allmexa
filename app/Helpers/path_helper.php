<?php

if (!function_exists('base_dir')) {
    function base_dir(string $path = null)
    {
        return FCPATH . $path;
    }
}

if (!function_exists('public_dir')) {
    function public_dir(string $path = null)
    {
        $basedir = base_dir();
        return "{$basedir}{$path}";
    }
}

if (!function_exists('upload_dir')) {
    function upload_dir(string $path = null)
    {
        $basedir = base_dir();
        return "{$basedir}uploads/{$path}";
    }
}