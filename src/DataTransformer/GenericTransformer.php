<?php

declare(strict_types = 1);

namespace App\DataTransformer;

class GenericTransformer implements DataTransformerInterface
{
    private $specialKeys = ['addRootNode', 'root'];

    /**
     * @param array $inputParametersMap
     * @param array|null $responseMap
     */
    public function __construct(private array $inputParametersMap, private ?array $responseMap)
    {
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function transformInputParameters(array $params): array
    {
        if ($this->inputParametersMap === null) {
            return $params;
        }

        if (!is_array($this->inputParametersMap)) {
            return $params;
        }

        $result = [];
        if ($this->inputParametersMap['queryParameters'] ?? null) {
            $result['query'] = $this->mapQueryParameters($params, $this->inputParametersMap['queryParameters']);
        }

        if ($this->inputParametersMap['headers'] ?? null) {
            $result['headers'] = $this->mapHeaders($params, $this->inputParametersMap['headers']);
        }

        $result['verb'] = 'GET';
        if ($this->inputParametersMap['body'] ?? null) {
            $result['verb'] = 'POST';
            $result['body'] =  $this->mapBody($params, $this->inputParametersMap['body']);
        }

        if ($this->inputParametersMap['pathParameters'] ?? null) {
            $result['pathParameters'] =  $this->mapPathParameters($params, $this->inputParametersMap['pathParameters']);
        }

        return $result;
    }

    /**
     * @param array $response
     *
     * @return array
     */
    public function transformResponse(array $response): array
    {
        $result = [];
        if (!is_array($this->responseMap)) {
            return $response;
        }
        if (($this->responseMap['addRootNode'] ?? false) === true) {
            $result['name'] = $this->responseMap['root'];
            $result['nodes'] = $this->transformNodes($response, []);
        } else {
            $result = $this->transformNodes($response, $result);
        }


        return $result;
    }

    /**
     * @param array $response
     *
     * @return array
     */
    private function transformDataSet(array $response): array
    {
        $result = [];

        foreach ($this->responseMap as $key => $value) {
            if (is_array($value)) {
                if ($value['isRecursive'] ?? null) {
                    $result[$key] = [];
                    foreach ($response[$value['key']] as $dataSet) {
                        $result[$key][] = $this->transformDataSet($dataSet ?? []) ?? [];
                    }
                }
            } else {
                if (is_string($value) && str_starts_with($value, '@') && (!in_array($key, $this->specialKeys))) {
                    $result[$key] = substr($value, 1) ?? null;

                    continue;
                }
                $result[$key] = $response[$value] ?? null;
            }
        }

        return $result;
    }

    /**
     * @param array $params
     * @param array $queryParameters
     *
     * @return array
     */
    function mapQueryParameters(array $params, array $queryParameters): array
    {
        return $this->genericMapping($params, $queryParameters);
    }

    /**
     * @param array $params
     * @param array $headers
     *
     * @return array
     */
    private function mapHeaders(array $params, array $headers): array
    {
        return $this->genericMapping($params, $headers);
    }

    /**
     * @param array $params
     * @param array $map
     *
     * @return array
     */
    private function genericMapping(array $params, array $map): array
    {
        $result = [];

        foreach ($map as $key => $value) {
            if (is_numeric($value)) {
                $result[$key] = $value;
                continue;
            }
            if (is_bool($value)) {
                $result[$key] = $value;
                continue;
            }
            if (is_string($value) && str_starts_with($value, '@')) {
                $result[$key] = substr($value, 1) ?? null;
                continue;
            }
            $result[$key] = $params[$value] ?? null;
        }

        return $result;
    }

    /**
     * @param array $params
     * @param mixed $body
     *
     * @return array
     */
    private function mapBody(array $params, mixed $body): array
    {
        return $this->genericMapping($params, $body);
    }

    /**
     * @param array $params
     * @param array $pathParameters
     *
     * @return array
     */
    private function mapPathParameters(array $params, array $pathParameters): array
    {
        return $this->genericMapping($params, $pathParameters);
    }

    /**
     * @param array $response
     * @param array $result
     *
     * @return array
     */
    protected function transformNodes(array $response, array $result): array
    {
        if (($this->responseMap['isArray'] ?? null) === true) {
            foreach ($response as $dataSet) {
                $result[] = $this->transformDataSet($dataSet);
            }
        } else {
            $result = $this->transformDataSet($response);
        }

        return $result;
    }
}
