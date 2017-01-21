<?php

namespace app\commons;

use Yii;
use app\commons\Mailbox;

class Imap extends Mailbox
{

    private $_connection = [];

    /**
     * @param array
     * @throws InvalidConfigException on invalid argument.
     */
    public function setConnection($connection)
    {
        if (!is_array($connection)) {
            throw new InvalidConfigException('You should set connection params in your config. Please read yii2-imap doc for more info');
        }
        $this->_connection = $connection;
    }

    /**
     * @return array
     */
    public function getConnection()
    {
        $this->_connection = $this->createConnection($this->_connection );
        return $this->_connection;
    }


    public function createConnection()
    {
        $this->imapPath = $this->_connection['imapPath'];
        $this->imapLogin = $this->_connection['imapLogin'];
        $this->imapPassword = $this->_connection['imapPassword'];
        $this->serverEncoding = $this->_connection['serverEncoding'];
        $this->attachmentsDir = $this->_connection['attachmentsDir'];
        if($this->attachmentsDir) {
            if(!is_dir($this->attachmentsDir)) {
                throw new Exception('Directory "' . $this->attachmentsDir . '" not found');
            }
            $this->attachmentsDir = rtrim(realpath($this->attachmentsDir), '\\/');
        }
        return $this;
    }
}
