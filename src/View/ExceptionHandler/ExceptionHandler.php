<?php
namespace Tricolore\View\ExceptionHandler;

use Tricolore\Foundation\Application;
use Tricolore\Config\Config;
use Tricolore\Services\ServiceLocator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Carbon\Carbon;

class ExceptionHandler extends ServiceLocator
{
    /**
     * Filesystem
     * 
     * @var Symfony\Component\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }
    
    /**
     * Handle exception
     * 
     * @param \Exception $exception 
     * @param bool $return
     * @return void
     */
    public function handle($exception, $return = false)
    {
        http_response_code(500);

        $reflection = new \ReflectionClass(get_class($exception));

        if ($reflection->getName() !== 'Tricolore\Exception\ErrorException') {
            $this->logException($exception);
        }

        if (Application::getInstance()->getEnv() === 'prod') {
            return $this->get('view')->display('Exceptions', 'HandleClientException');
        }

        $error_file = $exception->getFile();
        $error_line = $exception->getLine();

        if ($reflection->getName() === 'Tricolore\Exception\ErrorException') {
            $error_file = $exception->getErrorFile();
            $error_line = $exception->getErrorLine();
        }

        $request = Request::createFromGlobals();

        $response = $this->get('view')->display('Exceptions', 'HandleDevException', [
            'exception' => $exception,
            'file_lines' => $this->getFileLines($error_file, $error_line - 5, 10),
            'error_line' => $error_line,
            'error_file' => $error_file,
            'exception_name' => $reflection->getShortName(),
            'path_info' => $request->getPathInfo()
        ], $return);

        return $response;
    }

    /**
     * Get file lines
     * 
     * @param string $file
     * @param int $start
     * @param int $length
     * @return array
     */
    private function getFileLines($file, $start, $length)
    {
        if ($this->filesystem->exists($file) === false) {
            return [];
        }

        $file_array = new \SplFileObject($file, 'r');
        $lines = iterator_to_array($file_array);

        array_unshift($lines, null);
        unset($lines[0]);

        return array_slice($lines, $start, $length, true);
    }

    /**
     * Log exception
     * 
     * @param \Exception $exception 
     * @return void
     */
    private function logException($exception)
    {
        $exception_log = str_repeat('-', 20) . ' LAST EXCEPTION LOG ' . str_repeat('-', 20) . PHP_EOL . PHP_EOL;
        $exception_log .= 'MESSAGE: ' . $exception->getMessage() . PHP_EOL;
        $exception_log .= 'FILE: ' . $exception->getFile() . PHP_EOL;
        $exception_log .= 'LINE: ' . $exception->getLine() . PHP_EOL;
        $exception_log .= 'TIME: ' . Carbon::now()->toDateTimeString() . PHP_EOL . PHP_EOL;
        $exception_log .= str_repeat('-', 20) . ' LAST EXCEPTION LOG ' . str_repeat('-', 20);

        if (Application::getInstance()->getEnv() !== 'test') {
            $this->filesystem->dumpFile(Application::createPath(Config::getParameter('directory.storage') . ':last_exception.txt'), $exception_log);
        }
    }
}
