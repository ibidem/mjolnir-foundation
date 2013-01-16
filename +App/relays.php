<?php namespace app;

$mockup = \app\CFS::config('mjolnir/layer-stacks')['html'];

\app\Relay::process('mjolnir:mockup.route', $mockup);
\app\Relay::process('mjolnir:mockup-errors.route', $mockup);
\app\Relay::process('mjolnir:mockup-form.route', $mockup);
