<?php
/**
 * @see http://www.yiiframework.com/forum/index.php/topic/36942-creating-a-common-mail-queue/page__pid__178101#entry178101
 * MailQueueCommand class file.
 *
 * @author Matt Skelton
 * @date 26-Jun-2011
 */

/**
 * Sends out emails based on the retrieved EmailQueue objects.
 */
class MailQueueCommand extends CConsoleCommand
{

    public function run($args)
    {
        $criteria = new CDbCriteria(array(
            'condition' => 'success=:success AND attempts < max_attempts',
            'params' => array(
                ':success' => 0,
            ),
        ));

        $queueList = EmailQueue::model()->findAll($criteria);

        /* @var $queueItem EmailQueue */
        foreach ($queueList as $queueItem)
        {
            $message = new YiiMailMessage();
            $message->setTo($queueItem->to_email);
            $message->setFrom(array($queueItem->from_email => $queueItem->from_name));
            $message->setSubject($queueItem->subject);
            $message->setBody($queueItem->message, 'text/html');

            if ($this->sendEmail($message))
            {
                $queueItem->attempts = $queueItem->attempts + 1;
                $queueItem->success = 1;
                $queueItem->last_attempt = new CDbExpression('NOW()');
                $queueItem->date_sent = new CDbExpression('NOW()');

                $queueItem->save();
            }
            else
            {
                $queueItem->attempts = $queueItem->attempts + 1;
                $queueItem->last_attempt = new CDbExpression('NOW()');

                $queueItem->save();
            }
        }
    }

    /**
     * Sends an email to the user.
     * This methods expects a complete message that includes to, from, subject, and body
     *
     * @param YiiMailMessage $message the message to be sent to the user
     * @return boolean returns true if the message was sent successfully or false if unsuccessful
     */
    private function sendEmail(YiiMailMessage $message)
    {
        $sendStatus = false;

        if (Yii::app()->mail->send($message) > 0)
            $sendStatus = true;

        return $sendStatus;
    }

}
