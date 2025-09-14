<?php

namespace App\Libraries;

final class MyLib
{

    public static function addArrayToObject(\stdClass $object, array $data): \stdClass
    {
        foreach ($data as $key => &$val)
            $object->$key = $val;

        return $object;
    }

    public static function getObjectFromArray(array $array): object
    {
        // only for associative arrays
        $obj = new \stdClass;
        foreach ($array as $key => &$val)
            $obj->$key = $val;
        return $obj;
    }


    public static function minifyHtmlCssJs(string $htmlResponse): string
    {
        $search = [
            '!/\*[^*]*\*+([^/][^*]*\*+)*/!', // Remove CSS comments
            '~(?:(?<!\S)//.*$)|(?:/\*[\s\S]*?\*/)~m', // Remove single-line JavaScript comments
            '/\s{2,}/', // Replace multiple consecutive spaces with a single space
            '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s' // Remove HTML comments, tabs, and leading spaces

        ];

        $replace = [
            '', // Replace CSS comments with an empty string
            ' ', // Replace single-line JavaScript comments with a single space
            ' ', // Replace multiple consecutive spaces with a single space
            '' // Remove HTML comments, tabs, and leading spaces
        ];

        return preg_replace($search, $replace, $htmlResponse);
    }


    public static function builderPagination(\CodeIgniter\Database\BaseBuilder &$builder, int $per_page = 20, string $pager_tempalte = 'default_ful'): \stdClass
    {
        //* Ci4 Only

        // page
        if (!($page = inputGet('page')) or !is_numeric($page) or ($page < 1))
            $page = 1;

        $builder_copy = clone $builder;
        $totalRecords = $builder_copy->countAllResults(reset: true);

        $totalPages = ceil($totalRecords / $per_page);

        $page = ($page < $totalPages) ? max(1, $page) : max(1, $totalPages);

        $offset = ($page - 1) * $per_page;

        $pager = \Config\Services::pager();


        $builder->limit($per_page, $offset);
        $data = $builder->get()->getResult();
        $pager->makeLinks($page, $per_page, $totalRecords, $pager_tempalte);

        $obj = new \stdClass;
        $obj->pager = &$pager;
        $obj->data = &$data;

        return $obj;
    }


    public static function obfuscateEmailAsterisk(string $email): string
    {
        // Split the email into username and domain parts
        list($username, $domain) = explode('@', $email);

        // Obfuscate the username based on its length
        $usernameLength = strlen($username);
        if ($usernameLength > 6) {
            $obfuscatedUsername = substr($username, 0, 3) . str_repeat('*', $usernameLength - 3);
        } else {
            $obfuscatedUsername = substr($username, 0, 2) . str_repeat('*', 5);
        }
        // Combine the obfuscated username and the original domain
        return $obfuscatedUsername . '@' . $domain;
    }

}