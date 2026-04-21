<?php

namespace App\Helpers\AjaxForm;

use Illuminate\Http\JsonResponse;

class Response
{
    /** @var array{type: string, title: ?string, text: ?string}|null */
    protected ?array $message = null;

    /** @var array<string, string[]>|null */
    protected ?array $validation = null;

    /** @var array{url: string, delay: int}|null */
    protected ?array $redirection = null;

    protected mixed $customData = null;

    public function message(string $type, ?string $title = null, ?string $text = null): static
    {
        $this->message = [
            'type' => $type,
            'title' => $title,
            'text' => $text,
        ];

        return $this;
    }

    /**
     * @param  array<string, string[]>  $errors
     */
    public function validation(array $errors): static
    {
        $this->validation = $errors;

        return $this;
    }

    public function redirection(string $url, int $delay = 0): static
    {
        $this->redirection = [
            'url' => $url,
            'delay' => $delay,
        ];

        return $this;
    }

    public function custom(mixed $data): static
    {
        $this->customData = $data;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [];

        if ($this->message) {
            $result['message'] = $this->message;
        }
        if ($this->validation) {
            $result['validation'] = $this->validation;
        }
        if ($this->redirection) {
            $result['redirection'] = $this->redirection;
        }
        if ($this->customData) {
            $result['custom'] = $this->customData;
        }

        return $result;
    }

    /**
     * @param  array<string, string>  $headers
     */
    public function jsonResponse(int $status = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse(
            $this->toArray(),
            $status,
            $headers,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
