<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase;
use Priblo\LaravelHasTags\Traits\HasTags;
use Priblo\LaravelHasTags\Models\Related;

/**
 * Class TraitUsageTest
 */
class TraitUsageTest extends TestCase
{

    /**
     * Test Tags without type
     */
    public function test_CreateTagsWithoutType()
    {
        $User = $this->createOneUser();
        $User2 = $this->createOneUser();

        $User->tag(['tag1', 'tag2', 'tag3', 'tag1']);
        $User2->tag(['tag1', 'tag2', 'tag3', 'tag1']);

        $this->assertSame(3, $User->tags->count());
        $this->assertSame(3, $User2->tags->count());

        $User2->unTag();

        $this->assertSame(3, $User->tags->count());
        $this->assertSame(0, $User2->tags->count());
    }

    /**
     * Test Tags with type
     */
    public function test_CreateTagsWithType()
    {
        $User = $this->createOneUser();
        $User2 = $this->createOneUser();

        $User->tag(['tag1', 'tag2', 'tag3', 'tag1', 'tag1', 'tag4'],'hashtag');
        $User2->tag(['tag1', 'tag2', 'tag3', 'tag1', 'tag1', 'tag4'],'hashtag');
        $User2->tag(['tag1', 'tag2', 'tag3', 'tag1', 'tag1', 'tag4']);

        $this->assertSame(4, $User->tagsWithType('hashtag')->count());
        $this->assertSame(4, $User->tags->count());
        $this->assertSame(4, $User2->tagsWithType(null)->count());

        $User->tag(['tag1', 'tag2', 'tag3', 'tag4', 'tag5', 'tag5'],'tagtag');

        $this->assertSame(5, $User->tagsWithType('tagtag')->count());
        $this->assertSame(4, $User->tagsWithType('hashtag')->count());
        $this->assertSame(9, $User->tags->count());

        $User->reTag(['tag1', 'tag2', 'tag3', 'tag4', 'tag5', 'tag5', 'tag6'],'tagtag');

        $this->assertSame(6, $User->tagsWithType('tagtag')->count());
        $this->assertSame(4, $User->tagsWithType('hashtag')->count());
        $this->assertSame(10, $User->tags->count());

        $User->untag('tagtag');

        $this->assertSame(0, $User->tagsWithType('tagtag')->count());
        $this->assertSame(4, $User->tagsWithType('hashtag')->count());
        $this->assertSame(4, $User->tags->count());
    }

    /**
     * Test Tags with type
     */
    public function test_TraitScopes()
    {
        $User = $this->createOneUser();
        $User2 = $this->createOneUser();

        $User->tag(['tag1', 'tag2', 'tag3', 'tag1', 'tag1', 'tag4'],'hashtag');
        $User2->tag(['tag1', 'tag2', 'tag3', 'tag1', 'tag1', 'tag4'],'hashtag');
        $User2->tag(['tag1', 'tag2', 'tag3', 'tag1', 'tag4', 'tag5']);

        $users = User::withAnyTag(['tag1'],'hashtag')->get();

        $this->assertSame(2, $users->count() );

        $this->assertSame(4, $users->first()->tags->count());
        $this->assertSame(5, $users->last()->tagsWithType(null)->count());
        $this->assertSame(9, $users->last()->tags->count());

        $users = User::withAnyTag(['tag4'])->get();
        $this->assertSame(1, $users->count() );
    }

    /**
     * Test Tags with type
     */
    public function test_TraitRelated()
    {
        // Related involve using a static method across all taggables, we need to clean the slate before doing any work
        $this->assertSame(0, User::all()->count());

        $User = $this->createOneUser();
        $User2 = $this->createOneUser();
        $User3 = $this->createOneUser();
        $User4 = $this->createOneUser();
        $User5 = $this->createOneUser();

        $User->tag(['tag1', 'tag2', 'tag3'],'hashtag');
        $User2->tag(['tag1', 'tag3', 'tag4'],'hashtag');
        $User3->tag(['tag1', 'tag6']);
        $User4->tag(['tag1', 'tag7'], 'hashtag');
        $User5->tag(['tag1', 'tag8']);

        $this->assertSame(['tag6', 'tag8'], array_keys(User::getRelatedByTag('tag1')));
        $this->assertSame(Related::class, get_class(User::getRelatedByTag('tag1')['tag6']));

        $this->assertSame(['tag2', 'tag3', 'tag4', 'tag7'], array_keys(User::getRelatedByTag('tag1', 'hashtag')));
    }

    /**
     * @return User
     */
    private function createOneUser()
    {
        $User = new User();
        $User->username = uniqid();
        $User->save();

        return $User;
    }

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        // call migrations specific to our tests, e.g. to seed the db
        // the path option should be relative to the 'path.database'
        // path unless `--path` option is available.
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__.'/../migrations'),
        ]);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
//            'driver' => 'mysql',
//            'database'=> 'lht',
//            'username' => 'homestead',
//            'password' => 'secret',
//            'host'=>'127.0.0.1',
//            'prefix'   => '',
        ]);

        Schema::create('users', function ($table) {
            $table->increments('user_id');
            $table->string('username');
            $table->timestamps();
        });
    }

    /**
     *
     */
    public function tearDown()
    {
        Schema::drop('users');
    }

    /**
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
            \Priblo\LaravelHasTags\LaravelServiceProvider::class,
        ];
    }
}

/**
 * Class User
 */
class User extends Eloquent
{
    use HasTags;

    protected $connection = 'testbench';

    protected $table = 'users';
    protected $primaryKey = 'user_id';
}