<?php
/**
 * CakeBaseReporter contains common functionality to all cake test suite reporters.
 *
 * CakePHP(tm) Tests <https://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.3
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

if (!class_exists('PHPUnit_TextUI_ResultPrinter')) {
    require_once 'PHPUnit/TextUI/ResultPrinter.php';
}

/**
 * CakeBaseReporter contains common reporting features used in the CakePHP Test suite
 *
 * @package       Cake.TestSuite.Reporter
 */
class CakeBaseReporter extends PHPUnit_TextUI_ResultPrinter {

/**
 * Headers sent
 *
 * @var bool
 */
    protected $_headerSent = false;

/**
 * Array of request parameters. Usually parsed GET params.
 *
 * @var array
 */
    public $params = array();

/**
 * Character set for the output of test reporting.
 *
 * @var string
 */
    protected $_characterSet;

/**
 * Does nothing yet. The first output will
 * be sent on the first test start.
 *
 * ### Params
 *
 * - show_passes - Should passes be shown
 * - plugin - Plugin test being run?
 * - core - Core test being run.
 * - case - The case being run
 * - codeCoverage - Whether the case/group being run is being code covered.
 *
 * @param string $charset The character set to output with. Defaults to UTF-8
 * @param array $params Array of request parameters the reporter should use. See above.
 */
    public function __construct($charset = 'utf-8', $params = array()) {
        if (!$charset) {
            $charset = 'utf-8';
        }
        $this->_characterSet = $charset;
        $this->params = $params;
    }

/**
 * Retrieves a list of test cases from the active Manager class,
 * displaying it in the correct format for the reporter subclass
 *
 * @return mixed
 */
    public function testCaseList() {
        $testList = CakeTestLoader::generateTestList($this->params);
        return $testList;
    }

/**
 * Paints the start of the response from the test suite.
 * Used to paint things like head elements in an html page.
 *
 * @return void
 */
    public function paintDocumentStart() {
    }

/**
 * Paints the end of the response from the test suite.
 * Used to paint things like </body> in an html page.
 *
 * @return void
 */
    public function paintDocumentEnd() {
    }

/**
 * Paint a list of test sets, core, app, and plugin test sets
 * available.
 *
 * @return void
 */
    public function paintTestMenu() {
    }

/**
 * Get the baseUrl if one is available.
 *
 * @return string The base URL for the request.
 */
    public function baseUrl() {
        if (!empty($_SERVER['PHP_SELF'])) {
            return $_SERVER['PHP_SELF'];
        }
        return '';
    }

/**
 * A test suite ended.
 *
 * @param PHPUnit_Framework_TestSuite $suite The suite that ended.
 * @return void
 */
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite) {
    }

/**
 * A test started.
 *
 * @param PHPUnit_Framework_Test $test The test that started.
 * @return void
 */
    public function startTest(PHPUnit_Framework_Test $test) {
    }

}
