<?php
/*
 * Copyright 2008 Samisa Abeysinghe
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class RestClient
{
    private $with_curl;

    const USER_AGENT = 'RESTClient';

    /*
     * Constructor of the RESTClient
     */
    public function __construct()
    {
        if (function_exists("curl_init")) {
            $this->with_curl = TRUE;
        } else {
            $this->with_curl = FALSE;
        }
    }

    /*
     * Call the HTTP 'GET' method
     * @param string $url URL of the service.
     * @param array $params request parameters, hash of (key,value) pairs
     * @return response string
     */
    public function get($url, $params = array())
    {
        if (!empty($params)) {
            $params_str = (strpos($url, '?') == false) ? "?" : '&';
            if (is_array($params)) {
                foreach ($params as $key => $value) {
                    $params_str .= urlencode($key) . "=" . urlencode($value) . "&";
                }
            } else {
                $params_str .= $params;
            }
        } else {
            $params_str = '';
        }


        $url .= $params_str;

        $result = "";

        if ($this->with_curl) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
            curl_setopt($curl, CURLOPT_USERAGENT, RESTClient :: USER_AGENT);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            $result = curl_exec($curl);
            curl_close($curl);
        } else {
            $opts = array(
                'http' => array(
                    'method' => "GET",
                    'header' => "User-Agent: " . RESTClient :: USER_AGENT . "\r\n"
                )
            );

            $context = stream_context_create($opts);

            $fp = fopen($url, 'r', false, $context);
            $result = fpassthru($fp);
            fclose($fp);
        }

        return $result;
    }

    /*
     * Call the HTTP 'POST' method
     * @param string $url URL of the service..
     * @param string $data request data
     * @param array $content_type the http content type
     * @return response string
     * //
     * http://www.mingrobot.com/curlopt_postfields/
     */
    public function post($url, $data, $content_type = "application/x-www-form-urlencoded")
    {
        $result = "";


        /**
         * CURLOPT_POSTFIELDS:
         * The full data to post in a HTTP “POST” operation.
         * To post a file, prepend a filename with @ and use the full path.
         * This can either be passed as a urlencoded string like ‘para1=val1&para2=val2&…’
         * or as an array with the field name as key and field data as value. If value is an array,
         * the Content-Type header will be set to multipart/form-data.
         */
        if (is_array($data)) {
            $data2 = '';
            foreach ($data as $key => $value) {
                $data2 .= urlencode($key) . "=" . urlencode($value) . "&";
            }
            $data = $data2;
        }

        if ($this->with_curl) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HTTPHEADER, Array(
                "Content-Type: " . $content_type
            ));
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_USERAGENT, RESTClient :: USER_AGENT);
            $result = curl_exec($curl);
            curl_close($curl);
        } else {
            $opts = array(
                'http' => array(
                    'method' => "POST",
                    'header' => "User-Agent: " . RESTClient :: USER_AGENT . "\r\n" .
                        "Content-Type: " . $content_type . "\r\n" .
                        "Content-length: " . strlen($data
                    ) . "\r\n",
                    'content' => $data
                ));

            $context = stream_context_create($opts);

            $fp = fopen($url, 'r', false, $context);
            $result = fpassthru($fp);
            fclose($fp);
        }

        return $result;
    }

    /*
     * Call the HTTP 'PUT' method
     * @param string $url URL of the service..
     * @param string $data request data
     * @return response string
     */
    public function put($url, $data, $content_type = "application/x-www-form-urlencoded")
    {
        $result = "";

        if ($this->with_curl) {

            $fh = fopen('php://memory', 'rw');
            fwrite($fh, $data);
            rewind($fh);

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_USERAGENT, RESTClient :: USER_AGENT);
            curl_setopt($curl, CURLOPT_INFILE, $fh);
            curl_setopt($curl, CURLOPT_INFILESIZE, strlen($data));
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_PUT, 1);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            $result = curl_exec($curl);
            curl_close($curl);

            fclose($fh);
        } else {
            $opts = array(
                'http' => array(
                    'method' => "PUT",
                    'header' => "User-Agent: " . RESTClient :: USER_AGENT . "\r\n" .
                        "Content-Type: " . $content_type . "\r\n" .
                        "Content-length: " . strlen($data
                    ) . "\r\n",
                    'content' => $data
                ));

            $context = stream_context_create($opts);

            $fp = fopen($url, 'r', false, $context);
            $result = fpassthru($fp);
            fclose($fp);
        }

        return $result;
    }

    /*
     * Call the HTTP 'DELETE' method
     * @param string $url URL of the service..
     * @param array $params request parameters, hash of (key,value) pairs
     */
    public function delete($url, $params)
    {
        $params_str = "?";
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $params_str .= urlencode($key) . "=" . urlencode($value) . "&";
            }
        } else {
            $params_str .= $params;
        }

        $url .= $params_str;

        $result = "";

        if ($this->with_curl) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "delete");
            curl_setopt($curl, CURLOPT_USERAGENT, RESTClient :: USER_AGENT);
            $result = curl_exec($curl);
            curl_close($curl);
        } else {
            $opts = array(
                'http' => array(
                    'method' => "DELETE",
                    'header' => "User-Agent: " . RESTClient :: USER_AGENT . "\r\n"
                )
            );

            $context = stream_context_create($opts);

            $fp = fopen($url, 'r', false, $context);
            $result = fpassthru($fp);
            fclose($fp);

        }

    }

}

