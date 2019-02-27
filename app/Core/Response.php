<?php

namespace App\Core;

class Response
{
    const KEY_CODE = 'code';
    const KEY_ERROR = 'error';
    const KEY_MESSAGE = 'message';
    const KEY_DATA = 'data';

    const RESP_CODE_BAD_REQUEST = 400;
    const RESP_CODE_UNAUTHORIZED = 401;
    const RESP_CODE_FORBIDDEN = 403;
    const RESP_CODE_NOT_FOUND = 404;
    const RESP_CODE_ERROR = 500;

    const ERR_CODE_NO_TOKEN = 1001;
    const ERR_CODE_NOT_ENOUGH_PARAM = 1002;
    const ERR_CODE_RESOURCE_NOT_FOUND = 1003;

    const EXCEPTION_NOT_ENOUGH_ARGUMENT = 'NOT_ENOUGH_ARGUMENT';

    private $success = false;
    private $keys = [];

    /**
     * @return Response
     */
    public function succeed()
    {
        $this->success = true;
        $this->setCode(200);
        return $this;
    }

    /**
     * @param int   $code
     * @param int[] $errors
     *
     * @return Response
     */
    public function failed($code = self::RESP_CODE_BAD_REQUEST, $errors = [])
    {
        $this->success = false;
        $this->setCode($code);
        $this->setErrors($errors);
        \Log::info(__METHOD__ . ' Returning fail response, path=[' . \Request::path() . '] '
            . 'params=[' . json_encode(request()->all()) . ']');
        return $this;
    }

    /**
     * @param int[] $errors
     *
     * @return Response
     */
    public function unauthorized($errors = [])
    {
        return $this->failed(self::RESP_CODE_UNAUTHORIZED, $errors);
    }

    public function isSuccess()
    {
        return $this->success == true;
    }

    /**
     * @param string $key
     * @param $value
     *
     * @return Response
     */
    public function setKey($key, $value)
    {
        $this->keys[$key] = $value;
        return $this;
    }

    public function getKey($key)
    {
        if (isset($this->keys[$key])) {
            return $this->keys[$key];
        }
        return null;
    }

    public function setCode($value)
    {
        return $this->setKey(self::KEY_CODE, $value);
    }

    public function getCode()
    {
        return $this->getKey(self::KEY_CODE);
    }

    public function setErrors($value = [])
    {
        return $this->setKey(self::KEY_ERROR, $value);
    }

    public function getError()
    {
        return $this->getKey(self::KEY_ERROR);
    }

    public function setData($value)
    {
        return $this->setKey(self::KEY_DATA, $value);
    }

    public function getData()
    {
        return $this->getKey(self::KEY_DATA);
    }

    public function getKeys()
    {
        return $this->keys;
    }

    public function setKeys($keys)
    {
        $this->keys = array_merge($this->keys, $keys);
        return $this;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function toApiOutput()
    {
        if ($this->isSuccess()) {
            return response()->json((object)$this->getData(), $this->getCode());
        }

        $respArray = [
            self::KEY_ERROR => $this->getError(),
        ];

        return response()->json((object)$respArray, $this->getCode());
    }
}
