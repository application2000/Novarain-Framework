<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/
namespace NRFramework;

defined('_JEXEC') or die;

/**
 *  Cleverly evaluate php code using a temporary file and without using the evil eval() PHP method
 */
class Executer
{
    /**
     * The php code is going to be executed
     *
     * @var string
     */
    private $php_code;

    /**
     * Class constructor
     *
     * @param string $php_code  The php code is going to be executed
     */
    public function __construct($php_code = null)
    {   
        $this->setPhpCode($php_code);
    }

    /**
     * Helper method to set the php code is about to be executed
     *
     * @param string $php_code
     *
     * @return void
     */
    public function setPhpCode($php_code)
    {
        $this->php_code = $php_code;
        return $this;
    }

    /**
     * Run function
     *
     * @return function
     */
    public function run()
    {
        $function_name = $this->getFunctionName();

        // Function doesn't exist. Let's create it.
		if (!function_exists($function_name))
		{
            if (!$this->createFunction())
            {
                return;
            }
        }

        // Call function
		return $function_name();
    }

    /**
     * Creates a temporary function in memory
     *
     * @return void
     */
    private function createFunction()
    {
        $function_name    = $this->getFunctionName();
        $function_content = $this->getFunctionContent();
        $temp_file        = $this->getTempPath() . '/' . $function_name;

		// Write function's content to a temporary file
		\JFile::write($temp_file, $function_content);

		// Include file
		include_once $temp_file;

		// Delete file
		if (!defined('JDEBUG') || !JDEBUG)
		{
			@chmod($temp_file, 0777);
			@unlink($temp_file);
        }

        return function_exists($function_name);
    }

    /**
     * Get temporary file content
     *
     * @return string
     */
    private function getFunctionContent()
    {
        $function_name = $this->getFunctionName();
        $variables = $this->getFunctionVariables();

		$contents = [
			'<?php',
			'defined(\'_JEXEC\') or die;',
			'function ' . $function_name . '() {',
			implode("\n", $variables),
            $this->php_code,
			';return true;}'
		];

		$contents = implode("\n", $contents);

		// Remove Zero Width spaces / (non-)joiners
		$contents = str_replace(
			[
				"\xE2\x80\x8B",
				"\xE2\x80\x8C",
				"\xE2\x80\x8D",
			],
			'',
			$contents
		);

		return $contents;
    }

    /**
     * Make user's life easier by initializing some Joomla helpful variables
     *
     * @return array
     */
    protected function getFunctionVariables()
    {
        return [
			'$app = $mainframe = JFactory::getApplication();',
			'$document = $doc = JFactory::getDocument();',
			'$database = $db = JFactory::getDbo();',
			'$user = JFactory::getUser();',
			'$Itemid = $app->input->getInt(\'Itemid\');'
        ];
    }

    /**
     * Construct a temporary function name
     *
     * @return string
     */
    private function getFunctionName()
    {
		return 'tassos_php_' . md5($this->php_code);
    }

    /**
     * Return Joomla temporary path
     *
     * @return void
     */
    private function getTempPath()
    {
		return \JFactory::getConfig()->get('tmp_path', JPATH_ROOT . '/tmp');
    }
}