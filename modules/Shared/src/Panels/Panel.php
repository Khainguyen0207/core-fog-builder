<?php

namespace Modules\Shared\Panels;

class Panel
{
    protected string $name = 'Setting';

    protected string $description = '';

    protected string $url = '#';

    protected string $buttonLabel = 'Setup';

    protected string $template = 'shared::panels.card';

    protected string $icon = 'bx bx-cog';

    public static function make(?string $name = null): static
    {
        return app(static::class)->setup($name);
    }

    public function setup(?string $name = null): static
    {
        $this->name = $name ?? 'Setting';
        $this->description = '';
        $this->url = '#';
        $this->buttonLabel = 'Setup';
        $this->template = 'shared::panels.card';
        $this->icon = 'bx bx-cog';

        return $this;
    }

    public function setTemplate(string $template): static
    {
        $this->template = $template;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
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
