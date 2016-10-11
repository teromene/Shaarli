<?php

/**
 * PluginArchiveorgTest.php
 */

require_once 'plugins/archiveorg/archiveorg.php';

/**
 * Class PlugQrcodeTest
 * Unit test for the QR-Code plugin
 */
class PluginArchiveorgTest extends PHPUnit_Framework_TestCase
{
    /**
     * Reset plugin path
     */
    function setUp()
    {
        PluginManager::$PLUGINS_PATH = 'plugins';
    }

    /**
     * Test render_linklist hook.
     */
    function testArchiveorgLinklist()
    {
        $str1 = 'http://randomstr.com/test';
        $str2 = 'http://shaarli.test/?aaaaaa';
		$str2_real_url = '?aaaaaa';

        $data = array(
            'title' => $str1,
            'links' => array(
                array(
                    'url' => $str1,
					'private' => 0,
					'real_url' => $str1
                ),
				array(
                    'url' => $str2,
					'private' => 0,
					'real_url' => $str2_real_url
                ),
				array(
                    'url' => $str2,
					'private' => 1,
					'real_url' => $str2_real_url
                ),
            )
        );

        $data = hook_archiveorg_render_linklist($data);

        $link = $data['links'][0];
        // data shouldn't be altered
        $this->assertEquals($str1, $data['title']);
        $this->assertEquals($str1, $link['url']);

        // plugin data
        $this->assertEquals(1, count($link['link_plugin']));
        $this->assertNotFalse(strpos($link['link_plugin'][0], $str1));

		//Second link : internal public link, plugin datas should be here
		$link = $data['links'][1];
        $this->assertEquals(1, count($link['link_plugin']));
        $this->assertNotFalse(strpos($link['link_plugin'][0], $str2));

		//Third link : internal private link, plugin datas shouldn't be here
		$link = $data['links'][2];
        $this->assertFalse(isset($link['link_plugin']));
    }
}
