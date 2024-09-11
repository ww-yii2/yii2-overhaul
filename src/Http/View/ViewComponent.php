<?php
declare(strict_types=1);

namespace WebWizardry\Yii2\Http\View;

use yii\helpers\Html;
use yii\web\View;

/**
 * @property-read Html $html
 *
 * @author Alexey Volkov <webwizardry@hotmail.com>
 */
class ViewComponent extends View
{
    public function __construct(array $config = [])
    {
        $config['theme'] ??= [
            'class' => ViewTheme::class,
        ];
        parent::__construct($config);
    }

    public function getHtml(): string
    {
        if (method_exists($this->theme, 'getHtml')) {
            return $this->theme->getHtml();
        }
        return Html::class;
    }

    public function beforeRender($viewFile, $params): bool
    {
        if (method_exists($this->theme, 'registerAssets')) {
            $this->theme->registerAssets($this);
        }
        return parent::beforeRender($viewFile, $params);
    }
}