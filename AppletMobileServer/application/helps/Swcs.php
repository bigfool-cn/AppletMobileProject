<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/3/6
 * Time: 11:40
 */

namespace app\helps;


class Swcs
{
    public static function getWords($text)
    {
        $sh = scws_open();
        scws_set_charset($sh, 'utf-8');
        scws_set_dict($sh, '/usr/local/scws/etc/dict.utf8.xdb');
        scws_set_rule($sh, '/usr/local/scws/etc/rules.utf8.ini');
        scws_set_ignore($sh,true);
        scws_send_text($sh, $text);
        $top = scws_get_tops($sh,15);
        $words = array_column($top,'word');
        return $words;
    }
}