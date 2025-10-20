<?php

namespace App\Support;

use App\Constants\AppConstants;

class AlertNotification
{
    private string $type = 'undefined';
    private array $messages = [];

    public function __construct()
    {
    }

    public function hasMessage(): bool
    {
        return !empty($this->messages);
    }

    public function addSuccess(string $message): self
    {
        if (strcmp($this->type, AppConstants::ALERT_SUCCESS) !== 0) {
            $this->type = AppConstants::ALERT_SUCCESS;
        }
        $this->messages[] = $message;

        return $this;
    }

    public function addInfo(string $message): self
    {
        if (strcmp($this->type, AppConstants::ALERT_INFO) !== 0) {
            $this->type = AppConstants::ALERT_INFO;
        }
        $this->messages[] = $message;

        return $this;
    }

    public function addWarning(string $message): self
    {
        if (strcmp($this->type, AppConstants::ALERT_WARNING) !== 0) {
            $this->type = AppConstants::ALERT_WARNING;
        }
        $this->messages[] = $message;

        return $this;
    }

    public function addError(string $message): self
    {
        if (strcmp($this->type, AppConstants::ALERT_DANGER) !== 0) {
            $this->type = AppConstants::ALERT_DANGER;
        }
        $this->messages[] = $message;

        return $this;
    }

    public function clear(): self
    {
        $this->messages = [];
        $this->type = 'undefined';

        return $this;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function setMessages(array $messages): self
    {
        $this->messages = $messages;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Return an array suitable for JSON/blade consumption:
     * ['type' => 'success', 'messages' => [...]]
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'messages' => $this->messages,
        ];
    }

    /**
     * Flash the alert into the session so Blade can pick it up.
     * Example in Blade: @if(session('alert')) ... @endif
     *
     * @param string $key session key (default: 'alert')
     * @return $this
     */
    public function flash(string $key = 'alert'): self
    {
        if (function_exists('session')) {
            session()->flash($key, $this->toArray());
        }

        return $this;
    }
}
