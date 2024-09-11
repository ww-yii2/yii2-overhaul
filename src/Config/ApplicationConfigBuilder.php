<?php
declare(strict_types=1);

namespace WebWizardry\Yii2\Config;

use Exception;
use Psr\Container\ContainerInterface;
use WebWizardry\Config\ApplicationConfigBuilderInterface;
use WebWizardry\Config\Builder\BuildOptions;
use WebWizardry\Config\Builder\BuildTaskRunner;
use WebWizardry\Config\Helper\ArrayHelper;
use WebWizardry\Yii2\Components\ContainerComponent;
use WebWizardry\Yii2\Console\ConsoleApplication;
use WebWizardry\Yii2\Http\HttpApplication;
use WebWizardry\Yii2\Http\View\ViewComponent;

final class ApplicationConfigBuilder implements ApplicationConfigBuilderInterface
{
    /**
     * @throws Exception
     */
    public function run(
        BuildOptions $buildOptions,
        BuildTaskRunner $runner,
        ContainerInterface $container = null,
    ): array {
        if ( $plan = $buildOptions->getSection('yii2-application') ) {
            $config = ($task = ArrayHelper::pop($plan, 'template'))
                ? $runner->run($task, promoteVariables: ['container' => $container])
                : [];

            foreach (['components', 'modules'] as $section) {
                if ($task = ArrayHelper::pop($plan, $section)) {
                    $config[$section] = $runner->run($task, promoteVariables: ['container' => $container]);
                }
            }

            if ($container) {
                $config['components']['di'] = [
                    'class' => ContainerComponent::class,
                    'container' => $container,
                ];
            }

            $config['basePath'] ??= $buildOptions->getRootPath() . '/src';
            $config['viewPath'] ??= $buildOptions->getRootPath() . '/resource/views';

            return 'cli' === php_sapi_name()
                ? $this->validateConsoleConfig($config, $buildOptions)
                : $this->validateHttpConfig($config, $buildOptions);
        }

        return [];
    }

    private function validateHttpConfig(array $config, BuildOptions $options): array
    {
        $config['id'] ??= 'yii2-http-app';
        $config['class'] ??= HttpApplication::class;

        $config['aliases']['@vendor'] ??= $options->getRootPath() . '/vendor';
        $config['aliases']['@bower'] ??= '@vendor/bower-asset';

        return $config;
    }

    private function validateConsoleConfig(array $config, BuildOptions $options): array
    {
        $config['id'] ??= 'yii2-console-app';
        $config['class'] ??= ConsoleApplication::class;

        $config['aliases']['@vendor'] ??= $options->getRootPath() . '/vendor';

        return $config;
    }
}