<?php
declare(strict_types=1);

namespace WebWizardry\Yii2;

use Exception;
use WebWizardry\Config\Builder\ConfigBuilder;
use WebWizardry\Config\Composer\ComposerFileException;
use WebWizardry\Yii2\Config\ApplicationConfigBuilder;
use yii\base\Application;
use Yii;

final readonly class ApplicationFactory
{
    private ConfigBuilder $configBuilder;

    /**
     * @throws ComposerFileException
     */
    public function __construct(
        private string $rootPath,
        private string $defaultYiiEnv = 'prod',
        private bool $defaultYiiDebug = false,
    ) {
        $this->configBuilder = new ConfigBuilder(
            $this->rootPath,
            new ApplicationConfigBuilder()
        );
    }

    /**
     * @throws Exception
     */
    public function createApplication(): Application
    {
        defined('YII_DEBUG') or define('YII_DEBUG', $_ENV['YII_DEBUG'] ?? $this->defaultYiiDebug);
        defined('YII_ENV') or define('YII_ENV', $_ENV['YII_ENV'] ?? $this->defaultYiiEnv);

        $config = $this->configBuilder->run();

        require_once $this->rootPath . '/vendor/yiisoft/yii2/Yii.php';

        $app = Yii::createObject($config);
        assert($app instanceof Application);
        return $app;
    }
}