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

        $file_array = new \SplFileObject($error_file, 'r');

        $request = Request::createFromGlobals();

        $response = $this->get('view')->display('Exceptions', 'HandleDevException', [
            'exception' => $exception,
            'file_array' => iterator_to_array($file_array),
            'error_line' => $error_line,
            'error_file' => $error_file,
            'exception_name' => $reflection->getShortName(),
            'path_info' => $request->getPathInfo()
        ], $return);

        return $response;
    }

    /**
     * Log exception
     * 
     * @param \Exception $exception 
     * @return void
     */
    private function logException($exception)
    {
        $filesystem = new Filesystem();

        $exception_log = str_repeat('-', 20) . ' LAST EXCEPTION LOG ' . str_repeat('-', 20) . PHP_EOL . PHP_EOL;
        $exception_log .= 'MESSAGE: ' . $exception->getMessage() . PHP_EOL;
        $exception_log .= 'FILE: ' . $exception->getFile() . PHP_EOL;
        $exception_log .= 'LINE: ' . $exception->getLine() . PHP_EOL;
        $exception_log .= 'TIME: ' . Carbon::now()->toDateTimeString() . PHP_EOL . PHP_EOL;
        $exception_log .= str_repeat('-', 20) . ' LAST EXCEPTION LOG ' . str_repeat('-', 20);

        if (Application::getInstance()->getEnv() !== 'test') {
            $filesystem->dumpFile(Application::createPath(Config::getParameter('directory.storage') . ':last_exception.txt'), $exception_log);
        }
    }
}
