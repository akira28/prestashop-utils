<?php
/**
 * Clean all caches Shell Script
 *
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License
 * version 2.1 as published by the Free Software Foundation.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details at
 * http://www.gnu.org/copyleft/lgpl.html
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @package     Yameveo_Tools
 * @author      Andrea De Pirro <andrea.depirro@yameveo.com>
 * @version     1
 */

require(dirname(__FILE__) . '/../config/config.inc.php');
require(dirname(__FILE__) . '/abstract.php');

class Yameveo_Tools_CleanCache extends Yameveo_Tools_Abstract
{

    protected function cleanMergedJSCSS()
    {
        try {
            echo "Cleaning merged JS/CSS... ";
            flush();
            $this->_rrmdirContent('../' . _THEME_DIR_ . 'cache');
            echo "[OK]" . PHP_EOL . PHP_EOL;
        } catch (Exception $e) {
            die("[ERROR:" . $e->getMessage() . "]" . PHP_EOL);
        }
    }

    /**
     * Does a rmdir on:
     * Smarty cache
     * Smary compile
     * CacheFS
     *
     */
    protected function cleanFiles()
    {
        global $smarty;
        try {
            echo "Cleaning files:" . PHP_EOL;
            flush();
            echo "Smarty Cache... ";
            $this->_rrmdirContent($smarty->cache_dir);
            echo "[OK]" . PHP_EOL;
            echo "Smarty Compile... ";
            $this->_rrmdirContent($smarty->compile_dir);
            echo "[OK]" . PHP_EOL;
            if (is_dir(_PS_CACHEFS_DIRECTORY_)) {
                echo "Cachefs... ";
                $this->_rrmdirContent(_PS_CACHEFS_DIRECTORY_);
                echo "[OK]" . PHP_EOL;
            }
            echo PHP_EOL;
        } catch (Exception $e) {
            die("[ERROR:" . $e->getMessage() . "]" . PHP_EOL);
        }
    }

    protected function cleanAccelerator()
    {
        try {
            echo "Cleaning accelerator... ";
            flush();
            accelerator_reset();
            echo "[OK]" . PHP_EOL . PHP_EOL;
        } catch (Exception $e) {
            die("[ERROR:" . $e->getMessage() . "]" . PHP_EOL);
        }
    }

    protected function cleanAll()
    {
        $this->cleanMergedJSCSS();
        $this->cleanFiles();
        if (function_exists('accelerator_reset')) {
            $this->cleanAccelerator();
        }
    }

    /**
     * Run script
     *
     */
    public function run()
    {
        ini_set("display_errors", 1);
        $caches = array('js_css', 'files');
        if ($this->getArg('info')) {
            echo 'Allowed caches: ' . PHP_EOL;
            foreach ($caches as $cache) {
                echo "\t" . $cache . PHP_EOL;
            }
            die();
        }

        if ($this->getArg('all')) {
            $this->cleanAll();
            die();
        }

        if ($this->getArg('clean') && in_array($this->getArg('clean'), $caches)) {
            switch ($this->getArg('clean')) {
                case 'js_css':
                    $this->cleanMergedJSCSS();
                    break;
                case 'files':
                    $this->cleanFiles();
                    break;
            }
        } else {
            echo $this->usageHelp();
        }
    }

    /**
     * Removes all elements contained in the given directory
     * @param string $dir directory containing elements to remove
     */
    private function _rrmdirContent($dir)
    {
        $items = array_diff(scandir($dir), array('..', '.'));
        foreach ($items as $item) {
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            is_dir($path) ? $this->_rrmdir($path) : unlink($path);
        }
    }

    /**
     * Removes a directory and all elements contained
     * @param string $dir directory to remove
     */
    private function _rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = array_diff(scandir($dir), array('..', '.'));
            foreach ($objects as $object) {
                $path = $dir . DIRECTORY_SEPARATOR . $object;
                is_dir($path) ? $this->_rrmdir($path) : unlink($path);
            }
            reset($objects);
            rmdir($dir);
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php cleanCaches.php -- [options]

    --clean <cache>          Clean <cache>. Any of [js_css|files]
    all                      Clean all caches
    info                     Show allowed caches
    help                     This help


USAGE;
    }

}

$shell = new Yameveo_Tools_CleanCache();
$shell->run();