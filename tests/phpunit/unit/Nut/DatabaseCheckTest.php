<?php
namespace Bolt\Tests\Nut;

use Bolt\Nut\DatabaseCheck;
use Bolt\Tests\BoltUnitTest;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class to test src/Nut/DatabaseCheck.
 *
 * @author Ross Riley <riley.ross@gmail.com>
 */
class DatabaseCheckTest extends BoltUnitTest
{
    public function testRun()
    {
        $app = $this->getApp();
        $command = new DatabaseCheck($app);
        $tester = new CommandTester($command);

        $tester->execute([]);
        $result = $tester->getDisplay();
        $this->assertEquals("The database is OK.", trim($result));

        // Now introduce some changes
        $app['config']->set('contenttypes/newcontent', [
            'tablename' => 'newcontent',
            'fields'    => ['title' => ['type' => 'text']]
        ]);

        $tester->execute([]);
        $result = $tester->getDisplay();
        $this->assertRegExp("/Table `bolt_newcontent` is not present/", $result);
    }
}
