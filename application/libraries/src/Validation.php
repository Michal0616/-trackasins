<?php

/*
 * uVibe.com , Copyright 2014 All rights Reserved
 * @author James Latten
 * @desc uVibe file
 * This file is not to be givin to anyone else
 */

/**
 * Description of Validation
 *
 * @author james latten
 */
class Validation
{
    public function Santitize($data)
    {
        if (empty($data) != true) {
            $data = htmlentities($data);
            $data = strip_tags($data);
            $data = stripslashes($data);
            return $data;
        } else {
            return $data;
        }
    }

    public static function HumanReadableFileSizeConvert($bytes)
    {

        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }
        return $bytes;
    }

    public static function ComputerReadableFileSize($bytes)
    {

        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2);
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2);
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2);
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes;
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes;
        }
        else
        {
            $bytes = '0';
        }

    }

    public static function GetYoutubeId($data)
    {
        $url = str_replace('&amp;', '&', $data);
        if (strpos($url, 'http://www.youtube.com/embed/') !== false) // If Embed URL
        {
            return str_replace('http://www.youtube.com/embed/', '', $url);
        }
        parse_str(parse_url($url, PHP_URL_QUERY), $array_of_vars);
        $video_id = $array_of_vars['v'];

        if ($video_id != "") {
            // Now get other data
            $youtube = "http://www.youtube.com/oembed?url=" . $data . "&format=json";

            $curl = curl_init($youtube);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $return = curl_exec($curl);
            curl_close($curl);
            $videoData = json_decode($return, true);
        }

        return $video_id;
    }

    static public function RenderLinks($body)
    {
        $pattern = array(
            '/((?:[\w\d]+\:\/\/)?(?:[\w\-\d]+\.)+[\w\-\d]+(?:\/[\w\-\d]+)*(?:\/|\.[\w\-\d]+)?(?:\?[\w\-\d]+\=[\w\-\d]+\&?)?(?:\#[\w\-\d]*)?)/', # URL
            '/([\w\-\d]+\@[\w\-\d]+\.[\w\-\d]+)/', # Email
            '/\[([^\]]*)\]/', # Bold
            '/\{([^}]*)\}/', # Italics
            '/_([^_]*)_/', # Underline
            '/\s{2}/', # Linebreak
        );
        $replace = array(
            '<a href="$1">$1</a>',
            '<a href="mailto:$1">$1</a>',
            '<b>$1</b>',
            '<i>$1</i>',
            '<u>$1</u>',
            '<br />'
        );
        $body = preg_replace($pattern, $replace, $body);
        return $body;
    }

    public static function byte_convert($size) {
      # size smaller then 1kb
      if ($size < 1024) return $size . ' Byte';
      # size smaller then 1mb
      if ($size < 1048576) return sprintf("%4.2f KB", $size/1024);
      # size smaller then 1gb
      if ($size < 1073741824) return sprintf("%4.2f MB", $size/1048576);
      # size smaller then 1tb
      if ($size < 1099511627776) return sprintf("%4.2f GB", $size/1073741824);
      # size larger then 1tb
      else return sprintf("%4.2f TB", $size/1073741824);
    }

    public function clean_file_name($name)
    {
        if (empty($name) != true) {
            return $this->Santitize(preg_replace("/(?![.=$'€%-])\p{P}/u", "", $name));
        }
    }

    public function isValidEmail($email)
    {
        if (!empty($email)) {
            //$email = strip_tags(stripslashes($email));

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            } else {
                return true;
            }
        }else{
            return false;
        }
    }

    public function nice_number($n)
    {
        // first strip any formatting;
        $n = (0 + str_replace(",", "", $n));

        // is this a number?
        if (!is_numeric($n)) {
            return false;
        }

        // now filter it;
        if ($n > 1000000000000) {
            return round(($n / 1000000000000), 1) . 'tr';
        } else if ($n > 1000000000) {
            return round(($n / 1000000000), 1) . 'b';
        } else if ($n > 1000000) {
            return round(($n / 1000000), 1) . 'm';
        } else if ($n > 1000) {
            return round(($n / 1000), 1) . 't';
        } else if ($n > 100) {
            return round(($n / 100), 1) . 'h';
        } else {
            return number_format($n);
        }

        return number_format($n);
    }
}
