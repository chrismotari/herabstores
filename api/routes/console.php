<?php

use Illuminate\Support\Facades\Schedule;

/*
| Housekeeping. Production runs `php artisan schedule:run` every minute (see deploy/README.md).
*/

// Abandoned guest carts (App\Models\Cart::prunable).
Schedule::command('model:prune')->daily()->at('03:00');

// Expired API tokens (mobile; SANCTUM_TOKEN_EXPIRATION) and password reset tokens.
Schedule::command('sanctum:prune-expired --hours=24')->daily()->at('03:10');
Schedule::command('auth:clear-resets')->daily()->at('03:20');

// Failed queue jobs older than a week.
Schedule::command('queue:prune-failed --hours=168')->daily()->at('03:30');
