<?php

namespace LaFourchette\Logger;

use LaFourchette\Entity\Vm;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class use to handle all log configuration for vagrant
 */
class VmLogger
{
    const LOG_FILE_MASK = 'vm-file-%s';

    protected $logDir = null;
    protected $vm = null;

    public function __construct(Vm $vm)
    {
        $this->vm = $vm;
    }

    public function createLogger()
    {
        if (!$this->vm) {
            throw new \Exception("You need to provide a vm entity for this logger");
        }

        $logger = new Logger('vm-channel'.$this->getVm()->getIdVm());
        $handler = new StreamHandler(self::getLogFile($this->getVm()->getIdVm()), Logger::INFO);
        //[%datetime%] : %message% %context% %extra%\n
        $handler->setFormatter(new \Monolog\Formatter\LineFormatter("[%datetime%] : %message%", 'Y-m-d H:i:s', true));
        $logger->pushHandler($handler);

        return $logger;
    }

    /**
     * Get Vm entity
     * @return \LaFourchette\Entity\Vm
     */
    public function getVm()
    {
        return $this->vm;
    }

    /**
     * Get log file name
     * @param  int    $idVm
     * @param  string $logDir
     * @return string Vm log filename
     */
    public static function getLogFile($idVm, $logDir = null)
    {
        if (!$logDir) {
            $logDir = __DIR__.'/../../../logs';
        }

        return sprintf($logDir.'/'.self::LOG_FILE_MASK.'.log', $idVm);
    }
}
