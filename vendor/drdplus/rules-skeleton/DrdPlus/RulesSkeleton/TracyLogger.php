<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use PHPMailer\PHPMailer\PHPMailer;
use Tracy\BlueScreen;
use Tracy\Dumper;
use Tracy\ILogger;

class TracyLogger implements ILogger
{

    /** @var string name of the directory where errors should be logged */
    private $logDirectory;
    /** @var string|array email or emails to which send error notifications */
    private $email;
    /** @var string sender of email notifications */
    private $fromEmail;
    /** @var string|int interval for sending email as text (like 2 days) or seconds */
    private $emailSnooze;
    /** @var PHPMailer */
    private $mailer;
    /** @var \Tracy\BlueScreen|null */
    private $blueScreen;
    /** @var string */
    private $lowestSeverityToSendEmail;

    /**
     * @param string $logDirectory
     * @param string $email
     * @param BlueScreen $blueScreen
     * @param PHPMailer $mailer
     * @param string $lowestSeverityToSendEmail
     * @param int $emailSnooze
     * @param string|null $fromEmail
     * @throws \DrdPlus\RulesSkeleton\Exceptions\InvalidLogDirectory
     */
    public function __construct(
        string $logDirectory,
        string $email,
        BlueScreen $blueScreen,
        PHPMailer $mailer,
        string $lowestSeverityToSendEmail = self::ERROR,
        $emailSnooze = -1, // no snooze at all
        string $fromEmail = null // "noreply@{$host}" as default
    )
    {
        if (!\file_exists($logDirectory)) {
            throw new Exceptions\InvalidLogDirectory('Log directory does not exists: ' . $logDirectory);
        }
        $this->logDirectory = $logDirectory;
        $this->email = $email;
        $this->blueScreen = $blueScreen;
        $this->lowestSeverityToSendEmail = $lowestSeverityToSendEmail;
        $this->mailer = $mailer;
        $this->emailSnooze = $emailSnooze;
        $this->fromEmail = $fromEmail;
    }

    /**
     * Logs message or exception to file and sends email notification.
     *
     * @param string|\Exception|\Throwable
     * @param string one of constant
     * @see ILogger::INFO, ILogger::WARNING, ILogger::ERROR, ILogger::EXCEPTION, ILogger::CRITICAL
     * @return string|null logged error filename
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function log($message, $severity = self::INFO): ?string
    {
        $exceptionFile = $this->logToFile($message, $severity);
        if ($this->lowestSeverityToSendEmail
            && $this->severityToNumber($severity) >= $this->severityToNumber($this->lowestSeverityToSendEmail)
        ) {
            $this->sendEmail($message, $severity, $exceptionFile);
        }

        return $exceptionFile;
    }

    /**
     * @param $message
     * @param string $severity
     * @return null|string
     * @throws \RuntimeException
     */
    private function logToFile($message, string $severity): ?string
    {
        $exceptionFile = $message instanceof \Throwable
            ? $this->getExceptionFile($message)
            : null;
        if ($exceptionFile && \file_exists($exceptionFile)) {
            return $exceptionFile; // same exception already logged
        }
        $line = $this->formatLogLine($message, $exceptionFile);
        $file = $this->logDirectory . '/' . \strtolower($severity ?: self::INFO) . '.log';
        if (!@\file_put_contents($file, $line . PHP_EOL, FILE_APPEND | LOCK_EX)) {
            throw new \RuntimeException(
                "Unable to write to log to file '$file'. Does directory '$this->logDirectory' exist and is writable by "
                . \posix_getlogin()
            );
        }
        if ($exceptionFile) {
            $this->logExceptionToFile($message, $exceptionFile);
        }

        return $exceptionFile;
    }

    private function severityToNumber(string $severity): int
    {
        switch ($severity) {
            case self::INFO :
                return 0;
            case self::WARNING :
                return 1;
            case self::EXCEPTION :
                return 2;
            case self::ERROR :
                return 3;
            case self::CRITICAL :
                return 4;
            default :
                return 3;
        }
    }

    /**
     * @param \Throwable
     * @return string
     */
    private function getExceptionFile(\Throwable $exception): string
    {
        $data = [];
        while ($exception) {
            $data[] = [
                \get_class($exception), $exception->getMessage(), $exception->getCode(), $exception->getFile(), $exception->getLine(),
                \array_map(function ($item) {
                    unset($item['args']);

                    return $item;
                }, $exception->getTrace()),
            ];
            $exception = $exception->getPrevious();
        }
        $hash = \md5(\serialize($data));
        $dir = \strtr($this->logDirectory . '/', '\\/', DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
        foreach (new \DirectoryIterator($this->logDirectory) as $file) {
            if (\strpos($file->getBasename(), $hash)) {
                return $dir . $file;
            }
        }

        return $dir . 'exception--' . @\date('Y-m-d--H-i') . "--$hash.html"; // @ timezone may not be set
    }

    /**
     * @param string|\Exception|\Throwable
     * @param null|string $exceptionFile
     * @return string
     */
    private function formatLogLine($message, ?string $exceptionFile): string
    {
        return \implode(
            ' ',
            [
                @\date('[Y-m-d H-i-s]'), // @ timezone may not be set
                \preg_replace('#\s*\r?\n\s*#', ' ', $this->formatMessage($message)),
                ' @  ' . $this->getSource(),
                $exceptionFile ? ' @@  ' . \basename($exceptionFile) : null,
            ]
        );
    }

    private function getSource(): string
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            return (!empty($_SERVER['HTTPS']) && \strcasecmp($_SERVER['HTTPS'], 'off') ? 'https://' : 'http://')
                . ($_SERVER['HTTP_HOST'] ?? '')
                . $_SERVER['REQUEST_URI'];
        }

        return 'CLI (PID: ' . \getmypid() . ')'
            . (empty($_SERVER['argv']) ? '' : ': ' . \implode(' ', $_SERVER['argv']));
    }

    /**
     * Logs exception to the file if file doesn't exist.
     *
     * @param \Throwable
     * @param string $file
     * @return string logged error filename
     */
    private function logExceptionToFile(\Throwable $exception, string $file): string
    {
        $this->blueScreen->renderToFile($exception, $file);

        return $file;
    }

    /**
     * @param string|\Exception|\Throwable
     * @param string $severity
     * @param null|string $logFile
     * @return bool
     */
    private function sendEmail($message, string $severity, ?string $logFile): bool
    {
        $snooze = \is_numeric($this->emailSnooze)
            ? $this->emailSnooze
            : @\strtotime($this->emailSnooze) - \time(); // @ timezone may not be set
        if ($this->email
            && ($snooze <= 0
                || (@\filemtime($this->logDirectory . '/email-sent-' . $severity) + $snooze < \time() // @ file may not exist
                    && @\file_put_contents($this->logDirectory . '/email-sent-' . $severity, \time()) // @ file may not be writable
                )
            )
        ) {
            $email = \implode(', ', (array)$this->email);

            return $this->sendEmailMessage($message, $email, $severity, $logFile);
        }

        return false;
    }

    /**
     * @param string|\Exception|\Throwable
     * @param string $emailTo
     * @param string $severity
     * @param string|null $logFile
     * @return bool
     */
    private function sendEmailMessage($message, string $emailTo, string $severity, string $logFile = null): bool
    {
        $host = \preg_replace('#[^\w.-]+#', '', $_SERVER['HTTP_HOST'] ?? \php_uname('n'));
        $parts = \str_replace(
            ["\r\n", "\n"],
            ["\n", PHP_EOL],
            [
                'subject' => "PHP {$severity}: A problem occurred on the server " . \php_uname('a'),
                'body' => $this->formatMessage($message) . "\n\nsource: " . $this->getSource(),
            ]
        );
        try {
            $this->mailer->setFrom($this->fromEmail ?: "noreply@$host");
        } catch (\Exception $exception) {
            try {
                $this->mailer->setFrom("noreply@$host");
            } catch (\Exception $exception) {
                $this->logEmailFailed($parts['subject'], $exception);

                return false;
            }
        }
        $this->mailer->addAddress($emailTo);
        $this->mailer->Subject = $parts['subject'];
        $this->mailer->Body = $parts['body'];
        $this->mailer->ContentType = 'text/plain';
        $this->mailer->CharSet = 'UTF-8';
        if ($logFile) {
            $logFileContent = @\file_get_contents($logFile);
            if ($logFileContent) {
                $gzipped = \gzencode($logFileContent);
                $tmpFile = \tmpfile();
                if ($tmpFile && \fwrite($tmpFile, $gzipped)) {
                    $attachment = stream_get_meta_data($tmpFile)['uri'];
                    \fflush($tmpFile);
                    try {
                        $this->mailer->addAttachment($attachment, $severity . '.html.gz', 'base64', 'application/gzip');
                    } catch (\Exception $exception) {
                    }
                    $this->mailer->action_function = [
                        function () use ($tmpFile, $attachment) {
                            \fclose($tmpFile);
                            @\unlink($attachment);
                        }
                    ];
                }
            }
        }

        try {
            $sent = $this->mailer->send();
            if (!$sent) {
                $this->logEmailFailed($parts['subject'], new \RuntimeException($this->mailer->ErrorInfo));
            } else {
                $this->logEmailSent($parts['subject']);
            }

            return $sent;
        } catch (\Exception $exception) {
            try {
                $this->logToFile($exception, self::WARNING);
            } catch (\RuntimeException $runtimeException) {
            }
            $this->logEmailFailed($parts['subject'], $exception);

            return false;
        }
    }

    private function logEmailFailed(string $emailSubject, \Exception $exception): bool
    {
        return (bool)@\file_put_contents(
            $this->logDirectory . '/email_failed.log',
            \date(\DATE_ATOM) . ' ' . $emailSubject . ': ' . $exception->getMessage() . '; ' . $exception->getTraceAsString()
        );
    }

    private function logEmailSent(string $emailSubject): bool
    {
        return (bool)@\file_put_contents($this->logDirectory . '/email_sent.log', \date(\DATE_ATOM) . ' ' . $emailSubject);
    }

    /**
     * @param  string|\Exception|\Throwable
     * @return string
     */
    private function formatMessage($message): string
    {
        if ($message instanceof \Throwable) {
            $traceAsString = $message->getTraceAsString();
            $backtrace = [];
            while ($message) {
                $backtrace[] = ($message instanceof \ErrorException
                        ? $this->getErrorName($message->getSeverity()) . ': ' . $message->getMessage()
                        : \get_class($message) . ': ' . $message->getMessage() . ($message->getCode()
                            ? ' #' . $message->getCode() : '')
                    ) . ' in ' . $message->getFile() . ':' . $message->getLine();
                $message = $message->getPrevious();
            }
            $messageText = \implode("\ncaused by ", $backtrace);
            $messageText .= ";\n" . $traceAsString;

        } elseif (!\is_string($message)) {
            $messageText = Dumper::toText($message);
        } else {
            $messageText = $message;
        }

        return \trim($messageText);
    }

    private function getErrorName(int $type)
    {
        static $types = [
            E_ERROR => 'Fatal Error',
            E_USER_ERROR => 'User Error',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_CORE_ERROR => 'Core Error',
            E_COMPILE_ERROR => 'Compile Error',
            E_PARSE => 'Parse Error',
            E_WARNING => 'Warning',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_WARNING => 'User Warning',
            E_NOTICE => 'Notice',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Strict standards',
            E_DEPRECATED => 'Deprecated',
            E_USER_DEPRECATED => 'User Deprecated',
        ];

        return $types[$type] ?? 'Unknown error';
    }
}