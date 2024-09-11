<?php
declare(strict_types=1);

namespace WebWizardry\Yii2\Components;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use yii\base\Component;
use LogicException;

class ContainerComponent extends Component
{
    private ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container): void
    {
        if (null === $this->container) {
            $this->container = $container;
        } else {
            throw new LogicException('Container is already set.');
        }
    }

    public function init(): void
    {
        $this->assertContainerIsSet();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get(string $id): mixed
    {
        $this->assertContainerIsSet();
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        $this->assertContainerIsSet();
        return $this->container->has($id);
    }

    private function assertContainerIsSet(): void
    {
        if (null === $this->container) {
            throw new LogicException('Container is not set.');
        }
    }
}