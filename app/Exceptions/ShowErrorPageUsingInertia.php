<?php

namespace App\Exceptions;

use Inertia\Response;
use Throwable;
use Inertia\Inertia;

trait ShowErrorPageUsingInertia
{
    public function render($request, Throwable $e): \Symfony\Component\HttpFoundation\Response|Response
    {
        $response = parent::render($request, $e);

        if (!app()->environment(['local', 'testing'])) {
            return Inertia::render('Error', ['status' => $response->status()])
                ->toResponse($request)
                ->setStatusCode($response->status());
        }

        return $response;
    }
}
