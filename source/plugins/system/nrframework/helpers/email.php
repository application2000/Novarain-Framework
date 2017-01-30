<?php 

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');

/**
 *  Novarain Framework Emailer
 */
class NREmail
{
   /**
     *  Class instance
     *
     *  @var  object
     */
    private static $instance;

    /**
     *  Joomla Global Mail Object
     *
     *  @var  object
     */
    private $mailer;

    /**
     *  Indicates the last error
     *
     *  @var  string
     */
    private $error;

    /**
     *  Required elements for a valid email object
     *
     *  @var  array
     */
    private $requiredKeys = array(
        "from_email",
        "from_name",
        "recipient",
        "subject",
        "body"
    );

    /**
     *  Class constructor
     */
    private function __construct()
    {
        $this->mailer = JFactory::getMailer();
    }

    /**
     *  Returns class instance
     *
     *  @return  object
     */
    public static function getInstance()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     *  Validates Email Object
     *
     *  @param   array  $email  The email object
     *
     *  @return  boolean        Returns true if the email object is valid
     */
    public function validate($email)
    {
        if (!is_array($email) || !count($email))
        {
            return false;
        }

        $valid = true;

        foreach ($this->requiredKeys as $key)
        {
            if (!isset($email[$key]) || empty($email[$key]))
            {
                $valid = false;
                $this->error = "Invalid $key in email object";
                break;
            }
        }

        return $valid;
    }

    /**
     *  Sending emails
     *
     *  @param   array  $email  The mail objecta
     *
     *  @return  mixed         Returns true on success. Throws exeption on fail.
     */
    public function send($email)
    {
        // Validate first the email object
        if (!$this->validate($email))
        {
            throw new Exception($this->error);
        }

        $mailer = $this->mailer;

        // Email Sender
        $mailer->setSender(
            array(
                $email["from_email"],
                $email["from_name"]
            )
        );

        // Recipient
        $mailer->addRecipient($email["recipient"]);

        // Reply-to
        if (isset($email["reply_to"]))
        {
            $mailer->addReplyTo($email["reply_to"]);
        }

        $mailer->isHTML(true);
        $mailer->setSubject($email["subject"]);
        $mailer->setBody($email["body"]);

        // Send mail
        $send = $mailer->Send();
        
        if ($send !== true)
        {
            $this->error = 'Error sending email: ' . $send->__toString();
            throw new Exception($this->error);
        }

        return true;
    }
}

?>