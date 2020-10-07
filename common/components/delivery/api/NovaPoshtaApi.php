<?php


namespace common\components\delivery\api;

/**
 * Class NovaPoshtaApi
 * @package common\components\delivery\api
 */
class NovaPoshtaApi extends \LisDev\Delivery\NovaPoshtaApi2
{
    /**
     * @param string $cityName
     * @param string $cityRef
     * @param int $page
     * @return mixed
     */
    public function getWarehouses($cityName = '', $cityRef = '', $page = 0)
    {
        return $this->request('Address', 'getWarehouses', array(
            'CityName' => $cityName,
            'CityRef' => $cityRef,
            'Page' => $page,
        ));
    }

    /**
     * @param string $cityName
     * @param string $cityRef
     * @param string $description
     * @return mixed
     * @throws \Exception
     */
    public function getWarehouse($cityName = '', $cityRef = '', $description = '')
    {
        $warehouses = $this->getWarehouses($cityName, $cityRef);

        $error = [];
        $data = [];

        if (is_array($warehouses['data'])) {
            $data = $warehouses['data'];
        }

        // Error
        (!$data) and $error = 'Warehouse was not found';

        // Return data in same format like NovaPoshta API
        return $this->prepare(
            array(
                'success' => empty($error),
                'data' => $data,
                'errors' => (array) $error,
                'warnings' => array(),
                'info' => array(),
            )
        );
    }

    /**
     * Prepare data before return it.
     *
     * @param string|array $data
     *
     * @return mixed
     */
    private function prepare($data)
    {
        // Returns array
        if ('array' == $this->format) {
            $result = is_array($data)
                ? $data
                : json_decode($data, true);
            // If error exists, throw Exception
            if ($this->throwErrors and array_key_exists('errors', $result) and $result['errors']) {
                throw new \Exception(is_array($result['errors']) ? implode("\n", $result['errors']) : $result['errors']);
            }
            return $result;
        }
        // Returns json or xml document
        return $data;
    }

    /**
     * Make request to NovaPoshta API.
     *
     * @param string $model  Model name
     * @param string $method Method name
     * @param array  $params Required params
     */
    private function request($model, $method, $params = null)
    {
        // Get required URL
        $url = 'xml' == $this->format
            ? self::API_URI.'/xml/'
            : self::API_URI.'/json/';

        $data = array(
            'apiKey' => $this->key,
            'modelName' => $model,
            'calledMethod' => $method,
            'language' => $this->language,
            'methodProperties' => $params,
        );
        $result = array();
        // Convert data to neccessary format
        $post = 'xml' == $this->format
            ? $this->array2xml($data)
            : json_encode($data);

        if ('curl' == $this->getConnectionType()) {
            $ch = curl_init($url);
            if (is_resource($ch)) {
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: '.('xml' == $this->format ? 'text/xml' : 'application/json')));
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                $result = curl_exec($ch);
                curl_close($ch);
            }
        } else {
            $result = file_get_contents($url, false, stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-type: application/x-www-form-urlencoded;\r\n",
                    'content' => $post,
                ),
            )));
        }

        return $this->prepare($result);
    }
}