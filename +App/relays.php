<?php namespace app;

$mockup = \app\CFS::config('mjolnir/layer-stacks')['raw'];

\app\Router::process('mjolnir:mockup.route', $mockup);
\app\Router::process('mjolnir:mockup-errors.route', $mockup);
\app\Router::process('mjolnir:mockup-form.route', $mockup);
