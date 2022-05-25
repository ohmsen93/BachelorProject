<?php


class BaseController
{
    // This controller is used as a base and other controller extends it so as to allow access to debugging tools, and error handling.

    /**
     * __call magic method.
     */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    /**
     * Get URI elements.
     *
     * @return array
     */
    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );

        return $uri;
    }

    /**
     * Get querystring params.
     *
     * @return array
     */
    protected function getQueryStringParams()
    {
        return parse_str($_SERVER['QUERY_STRING'], $query);
    }

    /**
     * Send API output.
     *
     * @param mixed  $data
     * @param string $httpHeader
     */
    protected function sendOutput($data, $httpHeaders=array())
    {
        header_remove('Set-Cookie');

        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }

        echo $data;
        exit;
    }

    protected function check_for_error_msg($errorMsg, $responseData, $errorHeader, $instance)
    {
        /**
         * Sort of a utility-method that Controllers use to output.
         * Contains a check for error message and then outputs.
         */

        // No errors, proceed to return some output
        if (!$errorMsg) {
            $instance->sendOutput($responseData, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        } // Else send the error
        else {
            $instance->sendOutput(
                json_encode(array('error' => $errorMsg)),
                array('Content-Type: application/json', $errorHeader)
            );
        }
    }

}