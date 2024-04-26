<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use InfyOm\Generator\Utils\ResponseUtil;

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
    public function sendResponse($result, $message)
    {
        return response()->json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return response()->json(ResponseUtil::makeError($error), $code);
    }

    public function sendSuccess($message)
    {
        return response()->json([
            'success' => true,
            'message' => $message
        ], 200);
    }

    public function fileUpload($folder, $image)
    {
        $extension = $image->getClientOriginalExtension();
        $name = $folder . '/' . uniqid() . '.' . $extension;
        Storage::putFile($name, $image);
        return $name;
    }

    public function fileUpdate($folder, $image, $old_image)
    {
        $this->fileDelete($old_image);
        return $this->fileUpload($folder, $image);
    }

    public function fileDelete($image)
    {
        if ($image && Storage::exists($image)) {
            Storage::delete($image);
        }
    }
}
