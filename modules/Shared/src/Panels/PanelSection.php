<?php

namespace Modules\Shared\Panels;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use InvalidArgumentException;

class PanelSection
{
    protected string $template = 'shared::panels.page';

    protected string $nameTable = '';

    protected array $panels = [];

    public static function __callStatic(string $method, array $arguments): mixed
    {
        return static::make()->{$method}(...$arguments);
    }

    public static function make(): static
    {
        return app(static::class)->setup();
    }

    public function setup(): static
    {
        $this->template = 'shared::panels.page';
        $this->nameTable = '';
        $this->panels = [];

        return $this;
    }

    protected function addPanel(string $key, mixed $panel): static
    {
        $this->panels[$key] = $panel;

        return $this;
    }

    protected function addPanels(array $panels): static
    {
        foreach ($panels as $key => $panel) {
            is_string($key) ? $this->addPanel($key, $panel) : $this->panels[] = $panel;
        }

        return $this;
    }

    protected function setTemplate(string $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function renderPanel(): View
    {
        $this->setup();

        if (trim($this->template) === '') {
            throw new InvalidArgumentException('Panel view must not be empty.');
        }

        if (! ViewFacade::exists($this->template)) {
            throw new InvalidArgumentException("View [{$this->template}] not found.");
        }

        return view($this->template, [
            'panelSection' => $this,
            'title' => $this->nameTable !== '' ? $this->nameTable : 'Example App',
        ]);
    }

    public function getNameTable(): string
    {
        return $this->nameTable;
    }

    public function setNameTable(string $name): static
    {
        $this->nameTable = $name;

        return $this;
    }

    public function getPanels(): array
    {
        return $this->panels;
    }
}
