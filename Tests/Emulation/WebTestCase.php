<?php

namespace Ivory\LuceneSearchBundle\Tests\Emulation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpKernel\Util\Filesystem;

/**
 * Web test case
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class WebTestCase extends BaseWebTestCase
{
    /**
     * @var boolean TRUE if the web test case has been initialized else FALSE
     */
    protected static $initialize = array();
    
    /**
     * Remove emulation cache & logs directories
     */
    protected static function initialize($environment)
    {
        if(!isset(self::$initialize[$environment]) || (isset(self::$initialize[$environment]) && self::$initialize[$environment]))
        {
            $filesystem = new Filesystem();
            $filesystem->remove(__DIR__.'/cache/'.$environment);
            $filesystem->remove(__DIR__.'/logs');
            
            self::$initialize[$environment] = true;
        }
    }
    
    /**
     *@override
     */
    protected static function getKernelClass()
    {
        $kernelClass = 'AppKernel';
        
        require_once __DIR__.DIRECTORY_SEPARATOR.$kernelClass.'.php';

        return $kernelClass;
    }
    
    /**
     * Gets the kernel container
     *
     * @return Symfony\Component\DependencyInjection\ContainerInterface
     */
    public static function createContainer(array $options = array('environment' => 'default'))
    {
        self::initialize($options['environment']);
        
        $kernel = self::createKernel($options);
        $kernel->boot();

        return $kernel->getContainer();
    }
}
