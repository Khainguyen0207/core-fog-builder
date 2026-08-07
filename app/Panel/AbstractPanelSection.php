<?php

namespace App\Panel;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

abstract class AbstractPanelSection
{
    protected string $template = 'components.panels.base';

    protected string $nameTable;

    protected array $panels;

    public static function __callStatic($method, $arguments)
    {
        $instance = static::make();

        return $instance->$method(...$arguments);
    }

    public static function make()
    {
        return app(static::class)->setup();
    }

    public function setup(): static
    {
        return $this;
    }

    protected function addPanel(string $key, string|array $value): static
    {
        $this->panels = array_merge($this->panels, [$key => $value]);

        return $this;
    }

    protected function addPanels(array $panels): static
    {
        foreach ($panels as $panel) {
            $this->panels[] = $panel;
        }

        return $this;
    }

    protected function setTemplate(string $template): static
    {
        $this->template = $template;

        return $this;
    }

    private function render(): array
    {
        if (! trim($this->template)) {
            throw new \InvalidArgumentException('Vew Panel not empty.');
        }

        if (\Illuminate\Support\Facades\View::exists($this->template)) {
            throw new \InvalidArgumentException("View [$this->template] not found.");
        }

        return [
            'panelSection' => $this,
            'title' => $this->getNameTable() ?? 'Example App',
        ];
    }

    public function renderPanel(): View|Application|Factory|\Illuminate\View\View
    {
        $this->setup();

        $view = 'admin.'.$this->template;

        return view($view, $this->render());
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
