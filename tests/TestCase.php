<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Debug output for tests - writes to stderr so it shows during test runs
     */
    protected function debug($message, $data = null): void
    {
        $output = "[DEBUG] {$message}";

        if ($data !== null) {
            $output .= ': '.(is_string($data) ? $data : json_encode($data, JSON_PRETTY_PRINT));
        }

        fwrite(STDERR, $output."\n");
    }

    /**
     * Debug a response object
     */
    protected function debugResponse($response): void
    {
        $this->debug('Response Status', $response->status());
        $this->debug('Response Body', $response->json());

        if ($response->status() >= 400) {
            $this->debug('Response Headers', $response->headers->all());
        }
    }
}
