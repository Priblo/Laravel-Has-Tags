<?php
use Orchestra\Testbench\TestCase;
use Priblo\LaravelHasTags\Libraries\Strings;

/**
 * Class TraitUsageTest
 */
class LibrariesUsageTest extends TestCase
{

    /**
     * Test that tags are extracted correctly from text
     */
    public function test_ExtractTags()
    {
        $hashtags = Strings::getHashtagsFromString('My #cat is #wonderful, how is yours #mate? ##hashtag');

        $this->assertEquals(['cat','wonderful','mate','hashtag'], $hashtags);
    }

}