<?php namespace Priblo\LaravelHasTags\Libraries;

/**
 * Class Tag
 * @package Priblo\LaravelHasTags\Models
 */
class Strings
{
    /**
     * Extract hashtags from strings
     *
     * @param string $string
     * @param bool $with_hashes
     * @return array
     */
    public static function getHashtagsFromString(string $string, bool $with_hashes = FALSE) : array
    {
        $hashtags= [];
        preg_match_all("/(#\w+)/u", $string, $matches);
        if ($matches)
        {
            $hashtagsArray = array_count_values($matches[0]);
            $hashtags = array_keys($hashtagsArray);
        }

        if($with_hashes === FALSE)
        {
            foreach($hashtags as $k => $hashtag)
            {
                $hashtags[$k] = str_replace('#', '', $hashtag);
            }
        }
        return $hashtags;
    }
}
