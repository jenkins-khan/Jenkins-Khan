<?php
/*
 * This file is part of the sfLESSPlugin.
 * (c) 2010 Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfLESSDependency unit tests
 *
 * @package    sfLESSPlugin
 * @subpackage lib
 * @author     Victor Berchet <victor@suumit.com>
 * @version    1.0.0
 */

include dirname(__FILE__).'/../../../../test/bootstrap/unit.php';

$t = new lime_test(8);

$dir = dirname(__FILE__);
$base = realpath($dir . '/../fixtures');

class sfTestLESSDependency extends sfLESSDependency
{
  // allow access to a protected method
  public function getDependency($file)
  {
    return $this->computeDependencies($file, array());
  }
}

try
{
  $d = new sfLESSDependency($dir, true);
  $t->pass("Accepts an absolute path");
}
catch (Exception $e)
{
  $t->fail("Accepts an absolute path");
}

try
{
  $d = new sfLESSDependency("../", true);
  $t->fail("Rejects a relative path");
}
catch (Exception $e)
{
  $t->pass("Rejects a relative path");
}

try
{
  $d = new sfLESSDependency($dir . "/nofolder", true);
  $t->fail("Rejects an unexisting path");
}
catch (Exception $e)
{
  $t->pass("Rejects an unexisting path");
}

$d = new sfTestLESSDependency($base, true);
$deps = $d->getDependency($base . '/root.less');

$t->is(count($deps), 4, "4 dependencies");

$deps = array_map("basename", $deps);

$t->ok(in_array('a.less', $deps), 'a.less');
$t->ok(in_array('b.less', $deps), 'b.less');
$t->ok(in_array('c.c.less', $deps), 'c.c.less');
$t->ok(in_array('d.less', $deps), 'd.less');
