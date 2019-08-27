<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;
use PHPMailer\PHPMailer\PHPMailer;
use Tracy\BlueScreen;
use Tracy\Debugger;

class TracyDebugger extends StrictObject
{
    /**
     * @param bool $inProductionMode = null
     * @param TracyLogger|null $tracyLogger
     * @throws \DrdPlus\RulesSkeleton\Exceptions\InvalidLogDirectory
     */
    public static function enable(bool $inProductionMode = null, TracyLogger $tracyLogger = null): void
    {
        $tracyLogger = $tracyLogger ?? new TracyLogger(
                '/var/log/php/tracy',
                'info@drdplus.info',
                new BlueScreen(),
                self::getPhpMailer(),
                TracyLogger::INFO, // includes deprecated
                -1, // no snooze at all (but very same error is NOT reported twice)
                'noreply@drdplus.info' // from
            );
        Debugger::setLogger($tracyLogger);
        Debugger::enable($inProductionMode);
    }

    private static function getPhpMailer(): PHPMailer
    {
        $PHPMailer = new PHPMailer();
        if (\is_readable('/etc/php/smtp/config.ini')) {
            $smtpConfig = \parse_ini_file('/etc/php/smtp/config.ini');
            if ($smtpConfig) {
                $PHPMailer->isSMTP(); // set mailer to SMTP in fact
                $PHPMailer->Host = $smtpConfig['host'];
                $PHPMailer->Port = $smtpConfig['port'];
                $PHPMailer->Username = $smtpConfig['username'];
                $PHPMailer->Password = $smtpConfig['password'];
            }
        }

        return $PHPMailer;
    }
}