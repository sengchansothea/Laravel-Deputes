<?php
namespace App\Traits;
trait HttpResponsesTrait {
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($data, $message=null, $code=200)
    {
        $response = [
            'status' => true,
            'message' => $message,
            'data'    => $data,

        ];
        return response()->json($response, $code);

    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response

     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}



