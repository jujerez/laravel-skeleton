<?php

namespace App\Helpers;

use App\Helpers\AjaxForm\Response as AjaxFormResponse;

class AjaxForm
{
    public static function message(string $type, ?string $title = null, ?string $text = null): AjaxFormResponse
    {
        $response = new AjaxFormResponse;

        return $response->message($type, $title, $text);
    }

    public static function infoMessage(string $text, ?string $title = null): AjaxFormResponse
    {
        return self::message('info', $title ?? 'Info', $text);
    }

    public static function successMessage(string $text, ?string $title = null): AjaxFormResponse
    {
        return self::message('success', $title ?? 'Success', $text);
    }

    public static function warningMessage(string $text, ?string $title = null): AjaxFormResponse
    {
        return self::message('warning', $title ?? 'Warning', $text);
    }

    public static function errorMessage(string $text, ?string $title = null): AjaxFormResponse
    {
        return self::message('error', $title ?? 'Error', $text);
    }

    public static function validation(array $errors): AjaxFormResponse
    {
        $response = new AjaxFormResponse;

        return $response->validation($errors);
    }

    public static function redirection(string $url, int $delay = 0): AjaxFormResponse
    {
        $response = new AjaxFormResponse;

        return $response->redirection($url, $delay);
    }

    public static function custom(mixed $data): AjaxFormResponse
    {
        $response = new AjaxFormResponse;

        return $response->custom($data);
    }
}
