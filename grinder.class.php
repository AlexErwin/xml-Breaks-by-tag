<?php
class Grinder
{

    protected static $elemArray;
    protected static $file;
    protected static $tmp;

    public function __construct($file, $version = '1.0', $encoding = 'UTF-8')
    {

        self::$file = fopen($file, 'r');

    }

    public function countTags($tag) // todo: calculate count of tags 
    {

    }

    public function next($tag)
    {

        if (count(self::$elemArray) > 0) {// if have elemet

            $elemRaw = array_shift(self::$elemArray) . '</' . $tag . '>';// give first element

        } else {

            while (count(self::$elemArray) <= 0) {// repeat while have element

                if ($buff = fread(self::$file, 8000)) {

                    self::$tmp .= $buff; // add buffer to temp

                    if (mb_strpos(self::$tmp, '</' . $tag . '>') !== false) { // если в буфере есть тег то разбиваем его эксплодом

                        $arr = explode('</' . $tag . '>', self::$tmp);

                        self::$tmp = array_pop($arr); //add to temp last element 

                        self::$elemArray = $arr;

                    }

                } else {

                    return false;

                }

            }

            $elemRaw = array_shift(self::$elemArray) . '</' . $tag . '>';

        }
        preg_match("/<{$tag}[\s>]/", $elemRaw, $match);
        $result = mb_substr($elemRaw, mb_strpos($elemRaw, $match[0]));
        return $result;
    }


    public function reset()
    {
        rewind(self::$file);
        self::$tmp = '';
        self::$elemArray = [];
    }
}