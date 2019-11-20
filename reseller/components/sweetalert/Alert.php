<?php

namespace reseller\components\sweetalert;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Alert widget renders a message from session flash or custom messages.
 *
 * @package yii2mod\alert
 */
class Alert extends \yii2mod\alert\Alert
{
    /**
     * Initializes the widget
     */
    public function init()
    {
        parent::init();
        $types = [
            self::TYPE_INFO,
            self::TYPE_ERROR,
            self::TYPE_SUCCESS,
            self::TYPE_WARNING,
            self::TYPE_INPUT,
        ];

        if ($this->useSessionFlash) {
            $session = Yii::$app->getSession();
            $flashes = $session->getAllFlashes();

            foreach ($flashes as $type => $data) {
                if (!in_array($type, $types)) continue;
                $data = (array) $data;
                foreach ($data as $message) {
                    $this->options['type'] = $type;
                    $this->options['title'] = $message;
                }
                $session->removeFlash($type);
            }
        } else {
            if (!$this->hasTitle()) {
                throw new InvalidConfigException("The 'title' option is required.");
            }
        }
    }
}
