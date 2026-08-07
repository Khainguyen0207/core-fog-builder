<?php

namespace App\Panel;

abstract class AbstractPanel
{
    protected string $name;

    protected string $description;

    protected string $route;

    protected string $buttonLabel;

    protected string $template;

    protected string $icon = 'bx bx-cog';

    public static function make(?string $name = null): static
    {
        return app(static::class)->setup($name);
    }

    public function setup(): static
    {
        $this->name = 'Setting';
        $this->buttonLabel = 'Setup';

        $this->template = 'admin.components.panels.card';

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setButtonLabel(string $buttonLabel): static
    {
        $this->buttonLabel = $buttonLabel;

        return $this;
    }

    public function getButtonLabel(): string
    {
        return $this->buttonLabel;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function setUrl(string $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUrl(): string
    {
        return $this->route;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }
}
