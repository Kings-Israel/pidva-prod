<?php
/**
 * Created by PhpStorm.
 * User: mukuha
 * Date: 9/20/17
 * Time: 4:42 PM
 */


class Library
{

    /**
     * format mobile number
     *
     * @param string $mobile
     * @return mixed|string
     */
    public static function formatMobileNumber($mobile)
        {
            $mobile = str_replace("-","",$mobile);
            $mobile = preg_replace('/\s+/','',$mobile);
            $input = substr($mobile, 0, -strlen($mobile)+1);
            $number = false;

            if ($input == '0')
            {
                $number = substr_replace($mobile, '254', 0, 1);
                return $number;
            }
            elseif ($input == '+')
            {
                $number = substr_replace($mobile, '', 0, 1);
            }
            elseif ($input == '7')
            {
                $number = substr_replace($mobile, '2547', 0, 1);
            }
            if($number){
                $input = substr($number, 0, -strlen($number)+4);
                $mobile = $number;
            }
            else {
                $input = substr($mobile, 0, -strlen($mobile)+4);
            }

            if($input == 2547 && strlen($mobile) == 12) {
                return $mobile;
            }

            return false;
        }

    /**
     * remove unwanted characters in string
     *
     * @param string $text
     * @return mixed
     */
    public static function cleanString($text)
    {
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u' => ' ', // Literally a single quote
            '/[“”«»„]/u' => ' ', // Double quote
            '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        );

        return utf8_encode(preg_replace(array_keys($utf8), array_values($utf8), $text));
    }

    /**
     * hash code
     *
     * @param string $code
     * @return string
     */
    public static function hashCode($code){
        return md5($code);
    }


    /**
     * Sets the response code and reason
     *
     * @param int    $code
     * @param string $reason
     */
    public static function setResponse($data,$code, $reason = null) {

        if(is_array($data) || is_object($data)) {

            $data = json_encode($data);
        }

        header('Content-Type: application/json');  // <-- header declaration

        $code = intval($code);

        if (version_compare(phpversion(), '5.4', '>') && is_null($reason))
            http_response_code($code);
        else
            header(trim("HTTP/1.0 $code $reason"));


        echo $data;
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */

    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return $length === 0 || (substr($haystack, -$length) === $needle);
    }

    /**
     * curl post
     *
     * @param string $url
     * @param mixed $data
     * @return array
     */

	public static function curl($url, $data) {

		if(is_array($data) || is_object($data)){
			$data = json_encode($data);
		}

		$httpRequest = curl_init($url);
		curl_setopt($httpRequest, CURLOPT_NOBODY, true);
		curl_setopt($httpRequest, CURLOPT_POST, true);
		curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $data);
		curl_setopt($httpRequest, CURLOPT_TIMEOUT, 10);
		curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($httpRequest, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data)));
		$postresponse = curl_exec($httpRequest);
		$httpStatusCode = curl_getinfo($httpRequest, CURLINFO_HTTP_CODE); //get status code
		curl_close($httpRequest);


		$response = array(
			'httpStatus' => $httpStatusCode,
			'response' => $postresponse
		);

		$log = new Log();
		$log->debug("ENDPOINT " . $url . " payload " . json_encode($data) . " RESPONSE status " . $httpStatusCode . " message " . $postresponse);

		return $response;
	}

    public static function randomString($length = 6) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

	/**
	 * This function is searches through a string and try to find any placeholder variables,
	 * which would be place between two curly brackets {}. It grabs the value between the
	 * curly brackets and uses it to look through an array where it should match the key.
	 * Then it replaces the curly bracket variable in the string with the value in the
	 * array of the matching key.
	 *
	 *
	 * * @param $template - string with placeholders
	 * @param $data - replaceble values in an array
	 */

	public function replace($template, $data)
	{
		return strtr($template, $data);
	}
}