<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Traits\FileStorage;
use App\Traits\PushNotification;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    use ApiResponse;
    use FileStorage;
    use PushNotification;
}
