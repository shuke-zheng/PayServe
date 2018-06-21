<?php
/**
 * Created by PhpStorm.
 * User: 多牛_xiaojun
 * Date: 2018/6/20
 * Time: 17:26
 */

namespace Shuke\PayServe\Contracts;

interface PayServe{
    /**
     * Donews Payto
     * @param array $data
     * @return mixed
     */
    public function PayTo(array $data);

    /**
     * Donews Payover
     * @param array $data
     * @return error for 0 ,success for 1
     */
    public function PayOver(array $data);
}