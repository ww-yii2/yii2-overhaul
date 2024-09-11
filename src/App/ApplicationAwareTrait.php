<?php
declare(strict_types=1);

namespace WebWizardry\Yii2\App;

use WebWizardry\Yii2Old\Console\ConsoleApplication;
use WebWizardry\Yii2Old\Http\HttpApplication;
use Yii;

/**
 * @property-read HttpApplication|ConsoleApplication $app
 *
 * @author Alexey Volkov <webwizardry@hotmail.com>
 */
trait ApplicationAwareTrait
{
    public function getApp(): ConsoleApplication|HttpApplication
    {
        return Yii::$app;
    }
}