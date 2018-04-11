<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 11/04/2018
 * Time: 21:20
 */

spl_autoload_register(function ($name) {
    $name = str_replace("InstagramAmAPI", "src", $name);
    $name = str_replace("\\", DIRECTORY_SEPARATOR, $name);
    $name .= ".php";
    if (file_exists($name)) {
        include_once($name);
    }
});