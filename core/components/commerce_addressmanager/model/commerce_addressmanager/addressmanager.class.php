<?php

$loaderPath = dirname(dirname(__DIR__)) . '/vendor/autoload.php';
if (!file_exists($loaderPath)) {
    throw new Exception('Could not load autoloader, file does not exist at ' . $loaderPath
        . '. Are dependencies properly installed?');
}
// Setting the loader to a global allows us to store a reference to it in the service class.
global $loader;
$loader = require $loaderPath;

use PoconoSewVac\AddressManager\Frontend\Actions;

class AddressManager
{
    /**
     * @var modX
     */
    public $modx;

    /**
     * The Composer Autoloader instance.
     *
     * @var Composer\Autoload\ClassLoader $adapter
     */
    public $loader;

    /**
     * @var int
     */
    public $user;

    /**
     * @var Commerce
     */
    public $commerce;

    public $fields = [];
    public $config = [];

    /**
     * Initialize modX, Commerce, and user
     *
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX &$modx, array $config = array())
    {
        // Initialize AddressManager
        $this->modx = &$modx;
        $this->user = $config['user'];

        global $loader;
        $this->loader = &$loader;
        $this->loader->add('PoconoSewVac\\AddressManager\\', __DIR__);

        $basePath = $this->modx->getOption('commerce_addressmanager.core_path', $config, $this->modx->getOption('core_path') . 'components/commerce_addressmanager/');
        $assetsUrl = $this->modx->getOption('commerce_addressmanager.assets_url', $config, $this->modx->getOption('assets_url') . 'components/commerce_addressmanager/');
        $this->fields = $_REQUEST;

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'controllersPath' => $corePath . 'controllers/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'baseUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ]);

        $this->modx->addPackage('commerce_addressmanager', $this->config['modelPath']);

        // Load Commerce
        $commercePath = $this->modx->getOption('commerce.core_path', null, $this->modx->getOption('core_path') . 'components/commerce/') . 'model/commerce/';
        $commerceParams = ['mode' => $this->modx->getOption('commerce.mode')];
        $this->commerce = $this->modx->getService('commerce', 'Commerce', $commercePath, $commerceParams);
    }

    /**
     * Register frontend assets
     *
     * @param boolean $css Registers CSS
     * @param boolean $js Registers JS
     * @return void
     */
    public function registerAssets($css = true, $js = true)
    {
        if ($css) {
            $this->modx->regClientCSS($this->config['cssUrl'] . 'addressmanager.css');
        }
        if ($js) {
            $this->modx->regClientScript($this->config['jsUrl'] . 'addressmanager.js');
        }
    }

    /**
     * Get submitited field
     *
     * @param [type] $fieldName
     * @return void
     */
    public function getField($fieldName)
    {
        return $this->fields[$fieldName];
    }

    /**
     * Return type of request
     *
     * @return int
     */
    public function getAction()
    {
        // Determine how to route the request
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                if (intval($this->getField('add')) > 0) {
                    return new Actions\AddAddress($this->commerce->adapter, $this->user, $this->fields);
                } else if (intval($this->getField('delete')) > 0) {
                    return new Actions\DeleteAddress($this->commerce->adapter, $this->user, $this->fields);
                } else if (intval($this->getField('edit')) > 0) {
                    return new Actions\EditAddress($this->commerce->adapter, $this->user, $this->fields);;
                }

                break;

            case 'GET':
                if (intval($this->getField('view') > 0)) {
                    return new Actions\ViewAddress($this->commerce->adapter, $this->user, $this->fields);
                } else {
                    return new Actions\ViewAddresses($this->commerce->adapter, $this->user, $this->fields);
                }
                
                break;
        }
    }
}
