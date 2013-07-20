<?php namespace app;

$stacks = \app\CFS::config('mjolnir/layer-stacks');

$mockup = $stacks['raw'];

\app\Router::process('mjolnir:mockup.route', $mockup);
\app\Router::process('mjolnir:mockup-errors.route', $mockup);
\app\Router::process('mjolnir:mockup-form.route', $mockup);

$api = $stacks['json'];

\app\Router::process('mjolnir:api-500.route', $api);
\app\Router::process('mjolnir:api-404.route', $api);
