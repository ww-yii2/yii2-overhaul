<?php
declare(strict_types=1);

namespace WebWizardry\Yii2\Http\View;

use yii\base\Theme;
use yii\helpers\Html;
use yii\web\View;

class ViewTheme extends Theme
{
    public function __construct(array $config = [])
    {
        $config['basePath'] ??= dirname(__DIR__, 3) . '/resource/views';
        parent::__construct($config);
    }

    public function getHtml(): string
    {
        return Html::class;
    }

    public function registerAssets(View $view): void
    {
    }
}