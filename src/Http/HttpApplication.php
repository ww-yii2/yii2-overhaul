<?php
declare(strict_types=1);

namespace WebWizardry\Yii2\Http;

use WebWizardry\Yii2\App\ApplicationTrait;
use WebWizardry\Yii2\Http\View\ViewComponent;
use yii\helpers\ArrayHelper;
use yii\web\Application;

class HttpApplication extends Application
{
    use ApplicationTrait;

    public function coreComponents(): array
    {
        return ArrayHelper::merge(parent::coreComponents(), $this->commonCoreComponents(), [
            'view' => [
                'class' => ViewComponent::class
            ],
            'request' => [
                'cookieValidationKey' => $_ENV['COOKIE_VALIDATION_KEY']
                    ?? md5(__FILE__ . filemtime(__FILE__))
            ],
        ]);
    }
}